<?php
$imagesToShow = array();
$access       = 'public';

// Getting the images
if (isset($_GET["searchstring"]))
// only get images that show the search string
    $imagesToShow = getAllOccurences($_GET["searchstring"]);
else
// load entire json file into imagesToShow
    $imagesToShow = getJSON();

// set access based on post array
if (isset($_POST["access"])) {
    if ($_POST["access"] === "private")
        $access = "private";
    else if ($_POST["access"] === "all")
        $access = "all";
    else
        $access = "public";
}

if (isset($_GET['sort']))
    insertionSort($imagesToShow, $_GET['sort']);

// output images
if ($imagesToShow != null) {
    // if user is editor check to see if view is set
    if (isset($_SESSION['isEditor']) && $_SESSION['isEditor'] == 'true' && isset($_GET['view'])) {
        // images to update
        if (isValid($_GET['view'], 'edit')) {
            echo 
            '<div class="addPadding" id="viewType">
                <span class="glyphicon glyphicon-th-large addPadding" id="viewbuttonmultiple"></span>
                <span class="glyphicon glyphicon-picture addPadding" id="viewbuttonsingle"></span>
            </div>';
            printImagesToEdit($imagesToShow, $moderatorErrors);
        }
        // images to authorize/reject
        else if (isValid($_GET['view'], 'authorize'))
            printImagesToAuthorize($imagesToShow);
    }
    // if user isn't editor or if views aren't set prints all images
    else {
        printImages($imagesToShow, $access);
    }
} else echo '<h1>No Images Found<h1>';
?>