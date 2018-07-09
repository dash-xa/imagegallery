<?php
// constants
define('THUMBNAILS', 'thumbnails/'); // thumbnails folder
define('IMAGES', 'uploadedimages/'); // main images folder
define('IMAGECOUNTER', 'i.txt'); // contains # of uploaded images
define('JSON', 'galleryinfo.json'); // JSON data file
define('THUMB_HEIGHT', 150); // height of the thumbnails shown in gallery
define('ZIP', 'images.zip'); // zip file name
// writes contents from $arr to the json file
function write_to_json($arr)
{
    // encode the php array to json 
    $jsoncode = json_encode($arr, JSON_PRETTY_PRINT);
    // write the json to the file
    file_put_contents(JSON, $jsoncode);
}
// returns json file with JSON const. url
function getJSON()
{
    return json_decode(file_get_contents(JSON), true);
}
// checks if the word is valid
function check_valid($key, $data_value)
{
    switch ($key) {
        // check first name for anything other than letters
        case 'fname':
        case 'lname':
            return (preg_match('/^[a-zA-Z ]*$/', $data_value) && !empty($data_value) ? true : false);
        // check for valid image extension
        case 'imageupload':
            // verify extensions
            $extensions = array(
                'jpg',
                'jpeg',
                'png',
                'tif'
            );
            foreach ($extensions as $extension) {
                if (substr($data_value, strlen($data_value) - 3, strlen($data_value)) == $extension)
                    return true;
            }
            
            return false;
        // check if it's empty
        
        default:
            // clean data
            return (empty($data_value) ? false : true);
    }
}
// assigns the corresponding message with the key to the $errors array
function assignErrorAtKey(&$errors, $key, $modifiedKey = '')
{
    if ($modifiedKey == '')
        $modifiedKey = $key;
    switch ($key) {
        case 'fname':
            $errors[$modifiedKey] = 'Please enter a valid first name';
            break;
        case 'lname':
            $errors[$modifiedKey] = 'Please enter a valid last name';
            break;
        case 'description':
            $errors[$modifiedKey] = 'Please enter a description';
            break;
        case 'tags':
            $errors[$modifiedKey] = 'Please enter some valid tags';
            break;
        case 'copyright':
            $errors[$modifiedKey] = 'Please select this checkbox';
            break;
        case 'access':
            $errors[$modifiedKey] = 'Please indicate a type of access';
            break;
    }
}
// returns empty string if image has been uploaded, error message otherwise
function upload_and_verify_image($key, &$fields)
{
    $i             = file_get_contents(IMAGECOUNTER); // unique identifier
    $ext           = findexts($_FILES[$key]['name']); // extension
    $filename      = $i . '.' . $ext;
    $target_file   = IMAGES . $filename; // file name
    $imageVerified = verify_image($target_file, $i);
    if ($imageVerified == '') {
        move_uploaded_file($_FILES[$key]['tmp_name'], $target_file);
        // add image name
        $fields['imageupload'] = $filename;
        // make thumbnail   
        list($width, $height) = getimagesize($target_file);
        $aspectRatio = $width / $height;
        $newWidth    = THUMB_HEIGHT * $aspectRatio;
        $newImage    = resize_image($target_file, $newWidth, THUMB_HEIGHT);
        imagejpeg($newImage, THUMBNAILS . $filename);
    } else {
        return $imageVerified; // or echo error
    }
    // update i
    $i++;
    file_put_contents(IMAGECOUNTER, $i);
    return '';
}
// erases image from server
function delete_image($element, &$phparray)
{
    // remove element from array and unlink
    $imgIndex = getIndex($element, $phparray);
    array_splice($phparray, $imgIndex, 1);
    unlink(THUMBNAILS . $element);
    unlink(IMAGES . $element);
    // delete from zip
    $zip = new ZipArchive;
    if ($zip->open(ZIP) === TRUE) {
        $zip->deleteName(IMAGES . $element);
        $zip->close();
    }
}
// resizes image to given height and width
function resize_image($file, $newWidth, $newHeight)
{
    list($width, $height) = getimagesize($file);
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    if ($ext == "jpg")
        $src = imagecreatefromjpeg($file);
    else if ($ext == "png")
        $src = imagecreatefrompng($file);
    $dst = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    return $dst;
}
// checks if the image is the right size, is an actual image and whether or not it already exists
function verify_image($target_file, $i)
{
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION); // image file extension
    // Check file size
    if ($_FILES['imageupload']["size"] > 2000000 || $_FILES['imageupload']["size"] == 0)
        return "The image is too big";
    // Check if file already exists
    if (file_exists($target_file))
        return "File already exists, please upload a different image.";
    // Allow certain file formats
    if ($imageFileType != 'jpg' && $imageFileType != 'png')
        return "The image file type is not supported";
    return "";
}
// cleans up the data
function clean_data($data)
{
    $data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
// finds extension
function findexts($filename)
{
    $exts = substr($filename, strlen($filename) - 3, strlen($filename) - 1);
    return $exts;
}
// gets all occurrences of a string in the json array and returns an array of the matching elements (used for search)
function getAllOccurences($searchstring)
{
    // save the json data as a PHP array
    $phparray = getJSON();
    // array of found images
    $images   = array();
    // loop through json displaying matching images
    foreach ($phparray as $i)
        if (strpos($i["tags"], $searchstring) !== false)
            $images[] = $i;
    return $images;
}
// output thumbnail with fancybox
function generateThumbnail($imagedata)
{
    $src = THUMBNAILS . $imagedata['imageupload'];
    include 'fancyboximage.inc';
}
// generates a checkbox 
function generateCheckbox($imagedata)
{
    $src = THUMBNAILS . $imagedata['imageupload'];
    return '<input type="checkbox" name="' . $imagedata['imageupload'] . '" id="cb' . $src . '" 
            value="' . $imagedata['imageupload'] . '"/> <label for="cb' . $src . '"><img src="' . $src . '" />
            </label>';
}
// prints all the normal images in the gallery
function printImages($imagesToShow, $access)
{
    $areImages = false;
    // echo images from $imagesToShow
    foreach ($imagesToShow as $imagedata)
        if (($imagedata["access"] == $access || $access == "all") && $imagedata["authorized"] == true) {
            echo generateThumbnail($imagedata);
            $areImages = true;
        }
    if(!$areImages)  {
        if(isset($_GET['searchstring']))
            echo '<h1>No Images Found</h1>';
        else
            echo '<h1>No Images to Show!</h1>';
    }
}
// outputs all the images to authorize
function printImagesToAuthorize($images)
{
    $areImages = false;
    echo '<form name="imagesToAuthorize" action="index.php" method="post">';
    foreach ($images as $image)
        if ($image['authorized'] == false)
            $areImages = true;
    if ($areImages) {
        echo '<div><input class="btn btn-default" type="submit" name="authorize" value="Authorize"/>';
        echo '<input style="float:right" class="btn btn-default" type="submit" name="reject" value="Reject"/></div><br><br>';
    } else {
        echo '<h1>There are no images to authorize!</h1>';  
    }
    foreach ($images as $imagedata) {
        if ($imagedata['authorized'] !== true) {
            $src = THUMBNAILS . $imagedata['imageupload'];
            // echo image form for each image
            include 'imageinfo.inc';
        }
    }
    echo '</form>';
}
// outputs all the images to edit(moderator view)
function printImagesToEdit($images, $errors)
{
    $areImages = false;
    foreach($images as $image)
        if($image['authorized'] == true)
            $areImages = true;
    if(!$areImages) {
        echo '<h1>There are no images to edit</h1>';
        return;
    }
    echo '<form id="imagesToEdit" name="imagesToEdit" action="index.php" method="post">';
    echo '<input class="btn btn-default" id="submitButton" type="submit" name="update" value="Update"/><br><br>';
    if(isset($_GET['viewType']) && $_GET['viewType'] == 'single') {
        if(!isset($_GET['image']))
            $_GET['image'] = 0;
        
        $imgIndex = intval($_GET['image']);
        $imagedata = $images[$imgIndex];
        
        $src = THUMBNAILS . $imagedata['imageupload'];
        $next = $imgIndex+1;
        $prev = $imgIndex-1;
        $length = count($images);

        if($next > $length-1) $next = 0;
        if($prev < 0) $prev = $length-1;
        
        // left and right arrow
        echo '<a href="index.php?view=edit&viewType=single&image=' . $prev . '" class="arrow" id="arrowLeft"></a>';
        echo '<div class="center">';
        include 'singleimageinfo.inc';
        echo '</div>';
        echo '<a href="index.php?view=edit&viewType=single&image=' . $next . '" class="arrow" id="arrowRight"></a>';
    } else {
        foreach ($images as $imagedata) {
            if ($imagedata['authorized'] == true) {
                $src = THUMBNAILS . $imagedata['imageupload'];
                // print image form
                include 'imageinfo.inc';
            }
        }
    }
    echo '</form></div>';
}
// search through array until $phparray[i]['imageupload'] === $k
function getIndex($k, $phparray)
{
    for ($i = 0; $i < count($phparray); $i++) {
        if ($phparray[$i]['imageupload'] == $k)
            return $i;
    }
    return -1;
}
// remove image name from k
function cropKey(&$k)
{
    for ($i = 0; $i < strlen($k); $i++)
        if (is_numeric($k[$i]))
            $k = substr($k, 0, $i);
}
// makes browser download zip when called
function getZip()
{
    $dir     = "uploadedimages/*";
    $zipname = 'images.zip';
    $zip     = new ZipArchive;
    $zip->open($zipname, ZipArchive::CREATE);
    foreach (glob($dir) as $file)
        $zip->addFile($file);
    $zip->close();
    $file_url = 'images.zip';
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
    readfile($file_url); // do the double-download-dance (dirty but worky)
    unlink('images.zip');
}
// checks whether given value is valid input or not
function isValid($var, $value)
{
    if (!isset($var) || $var == null)
        return false;
    return (isset($var) && $var == $value) ? true : false;
}
// Sorting Algorithms
// insertion sort
function insertionSort(&$arr, $filter)
{
    for ($i = 1; $i < count($arr); $i++)
        for ($j = $i; $j > 0; $j--)
            if (less($arr, $filter, $j, $j - 1))
                swap($arr, $j, $j - 1);
            else
                break;
}
// compare imagesToShow
function less($arr, $filter, $a, $b)
{
    if ($filter === "firstname") {
        $isEqual = strcmp(strtolower($arr[$a]["fname"]), strtolower($arr[$b]["fname"]));
        if ($isEqual < 0)
            return true;
        else if ($isEqual > 0)
            return false;
        else
            return strcmp($arr[$a]["lname"], $arr[$b]["lname"]) < 0 ? true : false;
    } else if ($filter === "lastname") {
        $isEqual = strcmp(strtolower($arr[$a]["lname"]), strtolower($arr[$b]["lname"]));
        if ($isEqual < 0)
            return true;
        else if ($isEqual > 0)
            return false;
        else
            return strcmp($arr[$a]["fname"], $arr[$b]["fname"]) < 0 ? true : false;
    } else if ($filter === "date") {
        return strtotime(filemtime(IMAGES . $a['imageupload'])) < strtotime(filemtime(IMAGES . $b['imageupload'])) ? false : true;
    }
}
// swap 2 elements
function swap(&$arr, $a, $b)
{
    $temp    = $arr[$a];
    $arr[$a] = $arr[$b];
    $arr[$b] = $temp;
}
?>