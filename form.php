<?php
$showForm              = true; // whether or not to show the form
// data from the POST array
$fields                = array();
$fields["fname"]       = "";
$fields["lname"]       = "";
$fields["imageupload"] = "";
$fields["description"] = "";
$fields["tags"]        = "";
$fields["copyright"]   = "";
$fields["access"]      = "";
$fields["authorized"]  = false;
// contains error messages
$errors                = array();
$errors["fname"]       = "";
$errors["lname"]       = "";
$errors["imageupload"] = "";
$errors["description"] = "";
$errors["tags"]        = "";
$errors["copyright"]   = "";
$errors["access"]      = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $showForm = false;
    // add copyright and access to the array because they are otherwise not included
    if (!isset($_POST["copyright"]))
        $_POST["copyright"] = "";
    if (!isset($_POST["access"]))
        $_POST["access"] = "";
    // check $_POST array
    // go through array and either assign value to fields or make error message
    foreach ($_POST as $key => $value) {
        // if the image isn't empty
        if (check_valid($key, $value)) {
            $fields[$key] = clean_data($value);
        } else {
            assignErrorAtKey($errors, $key);
            $showForm = true;
        }
    }
    // check if image is valid and upload it
    $errors["imageupload"] = upload_and_verify_image("imageupload", $fields);
    if ($errors["imageupload"] !== "")
        $showForm = true;
}
// displays content
if ($showForm) {
    include "content.inc";
    $showForm = false;
} else {
    $phparray   = getJSON();
    $phparray[] = $fields;
    write_to_json($phparray);
    include "gallery.php";
}
?>