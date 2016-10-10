<?php
$target_dir = $_POST["folder"] . "/";
$target_file = $_SERVER['DOCUMENT_ROOT'] . "/" . $target_dir . basename($_FILES["NewFile"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["NewFile"]["tmp_name"]);
if($check !== false) {
	$uploadOk = 1;
} else {
	$uploadOk = 0;
}
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["NewFile"]["tmp_name"], $target_file)) {
        echo $target_file . "The image has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>