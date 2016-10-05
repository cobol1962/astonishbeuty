<?
// COPYRIGHT © 2001-2011 CreativeNetFX
// www.dogtreathouse.com
// Lead Developer: Tony Calhoun

session_start();

// Database Connection
$db = "astonbea_main";
$user = "astonbea_main";
$pass = "beautyZ!X@C#m0n9";

// xxxxxxxxxxxx No editng needed below here xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
$conn = mysql_connect("localhost",$user,$pass);
mysql_select_db($db);

$root = "/home/creativenetfx/public_html/demo/astonishing-beauty";
$product_images_dir = "products/";
$photo_gallery_dir = "photo_gallery/";
$thumbnail_size = "200";

$editor_upload_dir = "/cms_content/";

$dev_ip = "67.20.154.220";
?>