<?
session_start();
$ID = $_GET['ID'];

if ($ID) { 
  unset($_SESSION['cart'][$ID]);
}

header("location: checkout.php");
?>
