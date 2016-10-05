<?
session_start();
include("global.inc.php");

if (!is_array($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

$ID = mysql_escape_string($_POST['ID']);
$qty = mysql_escape_string($_POST['qty']); 
$color = mysql_escape_string($_POST['color']); 
$size = mysql_escape_string($_POST['size']); 
  
$item_sql = mysql_query("SELECT * from products where ID='$ID'");
$item_info = mysql_fetch_array($item_sql);
$name = $item_info['name'];
$sh = $item_info['sh'];
$use_size_as_qty = $item_info['use_size_as_qty'];

if (!$use_size_as_qty) {
  $options = $size;
  if ($size) { 
    $size_ary = explode("|",$size);
    $size = $size_ary[0];
    $size_price = $size_ary[1];  
  }
  
  if ($size_price) { 
    $price = $size_price * $qty;
  } else {
    $price = $item_info['price'] * $qty;
  }
} else {
  if ($size) { 
    $size_ary = explode("|",$size);
    $size = $size_ary[0];
    $size_price = $size_ary[1];  
  }
  $qty = $size;
  if (!$size_price) { $size_price = $item_info['price']; }
  $price = $size_price;
 
}

$eachprice = $price;

if ($size) { 
  $ID = $ID.str_replace(array(".",","),"",$size_price);
}

  
  // If already in cart, increase Qty & Pricing
  if (array_key_exists($ID,$_SESSION['cart'])) {
    $qty = $qty + $_SESSION['cart'][$ID]['qty'];
    $price = number_format($_SESSION['cart'][$ID]['price'] + $price,2);
    if ($sh != "2.50") { $sh = number_format($_SESSION['cart'][$ID]['sh'] + 1,2); }
  }
  
  
  // Add to Cart
  $_SESSION['cart'][$ID] = array("qty" => $qty, "name" => $name, "eachprice" => $eachprice, "price" => $price, "sh" => $sh, "options" => $options, "use_size_as_qty" => $use_size_as_qty);
  
  $_SESSION['frompg'] = $_REQUEST['frompg'];

if ($_SESSION['admin_auth']=="ok") {
  header("location: checkout2.php?chk=added");
} else { 
  header("location: checkout.php?chk=added");
}
?>