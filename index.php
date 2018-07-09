<?php
session_start();
include "functions.php";
// variables
$moderatorErrors = array(); // errors for the moderator updating the image info

// zip file
if (isset($_GET['downloadzip']))
    getZip();
if (isset($_GET['isEditor'])) {
    // if login button is clicked
    if ($_GET['isEditor'] === "true") {
        $_SESSION['isEditor'] = true;
    }
    // if logout button is clicked
    else {
        $_SESSION['isEditor'] = false;
        session_unset();
        session_destroy();
    }
}
include "header.inc";
// show form if get request says to show it
if (isset($_GET['form']) && $_GET['form'] == "true")
    include "form.php";
/* POST array could be multiple things:
1. Images to delete
2. Images to update
3. Images to authorize
*/
// if images are selected delete the selected ones
else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phparray = getJSON();
    // Images to update
    if (isset($_POST['update'])) {
        $imgIndex = 0;
        foreach ($_POST as $k => $v) {
            cropKey($k);
            if ($k != 'update') {
                if ($k == 'imagename')
                    $imgIndex = getIndex($v, $phparray);
                else {
                    if (check_valid($k, $v))
                        $phparray[$imgIndex][$k] = clean_data($v);
                }
            }
        }
    }
    // Images to delete
    else if (isset($_POST['delete'])) {
        foreach ($_POST as $key => $element) {
            cropKey($key);
            // remove element from array and unlink
            if ($key == 'imageToDelete')
                delete_image($element, $phparray);
        }
    }
    // Images to reject
    else if (isset($_POST['reject'])) {
        $imgIndex = 0;
        foreach ($_POST as $key => $element) {
            cropKey($key);
            if ($key == 'imageToDelete')
                delete_image($element, $phparray);
        }
    }
    // Images to authorize
    else if (isset($_POST['authorize'])) {
        $imgIndex = 0;
        foreach ($_POST as $key => $element) {
            // $origKey = $key;
            cropKey($key);
            $element = clean_data($element);
            if ($key == 'update')
                continue;
            
            if ($key == 'imageToDelete') {
                $imgIndex                          = getIndex($element, $phparray);
                $phparray[$imgIndex]['authorized'] = true;
                continue;
            }
            // check for errors
            $phparray[$imgIndex][$key] = $element;
        }
    }
    // write to json is there's no errors
    if (!isset($_GET['view']))
        write_to_json($phparray);
}
// if form isn't true include gallery
if (!isset($_GET['form']))
    include "gallery.php";

include "footer.inc";
?>