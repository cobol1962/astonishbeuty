<?
// PHP 4.1
session_start();
include("global.inc.php");
include("func.inc.php");

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}

// post back to PayPal system to validate
$header  = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
//$header .="Host: www.paypal.com\r\n"; 
//$header .="Connection: close\r\n\r\n";
$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

// assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$num_cart_items = $_POST['num_cart_items'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$shipping_amount = $_POST['mc_shipping'];
$payment_currency = $_POST['mc_currency'];
$payer_firstname = $_POST['first_name'];
$payer_lastname = $_POST['last_name'];
$name = $payer_firstname." ".$payer_lastname;
$payer_address = $_POST['address_street'];
$payer_city = $_POST['address_city'];
$payer_state = $_POST['address_state'];
$payer_zip = $_POST['address_zip'];
$phone = $_POST['contact_phone'];
$receipt_id = $_POST['receipt_id'];
$txn_id = $_POST['txn_id'];
$for_auction = $_POST['for_auction'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];
$custom = $_POST['custom'];
$cc_input_method = $_POST['cc_input_method'];
$txn_type = $_POST['txn_type'];

/*
    $vars .= $item_name."\n\n";
    $vars .= strcmp ($res, "VERIFIED")."\n\n";
    $vars .= $payment_status."\n\n";
    $vars .= $payment_amount."\n\n";
    $vars .= $shipping_amount."\n\n";
    $vars .= $receiver_email."\n\n";
    $vars .= $txn_used."\n\n";
    $vars .= $payment_currency."\n\n";
    
    mail("tony@tonycalhoun.com","testorder-dogreat",$vars,$headers);
*/

if (!$fp) {
  // HTTP ERROR
} else {
  fputs ($fp, $header . $req);
  while (!feof($fp)) {
    $res = fgets ($fp, 1024);
   
    if (strcmp ($res, "VERIFIED") == 0) {
    ///////////////////////// TRANSACTION VERIFIED FUNCTIONS


      if (
      $receiver_email=="dsharms@gmail.com" && $payment_status=="Completed" &&
         $for_auction!="true" && $payment_currency=="USD" && 
         $cc_input_method!="Scanned" && $cc_input_method!="Keyed-In" && $cc_input_method!="Keyed-In" &&
         $txn_type=="cart"
      ) {

      $ip = $_SERVER['REMOTE_ADDR'];
      
      /*
      $order_detail_ary = explode(",",$custom);
      foreach($order_detail_ary as $currValue) {
        $currItemAry = explode("-",$currValue);
        $order_detail_qty = $currItemAry[0];
        $order_detail_name = get_row("products","ID='".$currItemAry[1]."'","name");
        $order_detail .= $order_detail_qty." - ".$order_detail_name."<br />";
      }
   
       mysql_query("
       INSERT INTO orders (name,phone,email,shipping_name,shipping_address,shipping_city,shipping_state,shipping_zip,order_detail,order_total,order_sh_total,txn_id,ip) 
       VALUES 
       ('$name','$phone','$payer_email','$name','$payer_address','$payer_city','$payer_state','$payer_zip','$order_detail','$payment_amount','$shipping_amount','$txn_id','$ip')");
     */
       
       $order_detail = get_row("orders","ID='".$custom."'","order_detail");
       $order_shipping = get_row("orders","ID='".$custom."'","order_sh_total");
       mysql_query("UPDATE orders SET email='$payer_email', name='$name', shipping_name='$name', shipping_address='$payer_address', shipping_city='$payer_city', shipping_state='$payer_state', shipping_zip='$payer_zip', order_sh_total='$order_shipping', order_date=now(), txn_id='$txn_id', ip='$ip' WHERE ID='$custom'");
    
        //SEND RECEIPT TO OFFICE
        $message_body = file_get_contents("email_receipt_office.html");
        $message_body = str_replace ("{name}",$name,$message_body);
        $message_body = str_replace ("{address}",$payer_address,$message_body);
        $message_body = str_replace ("{city}",$payer_city,$message_body);
        $message_body = str_replace ("{state}",$payer_state,$message_body);        
        $message_body = str_replace ("{zip}",$payer_zip,$message_body);                        
        $message_body = str_replace ("{phone}",$phone,$message_body);                        
        $message_body = str_replace ("{email}",$payer_email,$message_body);
        $message_body = str_replace ("{receipt_ID}",$receipt_id,$message_body);                        
        $message_body = str_replace ("{order_detail}",$order_detail,$message_body);
        $message_body = str_replace ("{order_sh_total}",$order_shipping,$message_body);
        $message_body = str_replace ("{order_total}",$payment_amount,$message_body);        
        $subject = "New Order Received from Dog Treat House!";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= "From: orders@dogtreathouse.com" . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail("sawmillcreek.smokehouse@gmail.com",$subject,$message_body,$headers);
        mail("tony@creativenetfx.com",$subject,$message_body,$headers);

        //SEND RECEIPT TO CLIENT
        $message_body = file_get_contents("email_receipt_customer.html");
        $message_body = str_replace ("{name}",$name,$message_body);
        $message_body = str_replace ("{address}",$payer_address,$message_body);
        $message_body = str_replace ("{city}",$payer_city,$message_body);
        $message_body = str_replace ("{state}",$payer_state,$message_body);        
        $message_body = str_replace ("{zip}",$payer_zip,$message_body);                          
        $message_body = str_replace ("{phone}",$phone,$message_body);                        
        $message_body = str_replace ("{order_detail}",$order_detail,$message_body);
        $message_body = str_replace ("{order_sh_total}",$order_shipping,$message_body);
        $message_body = str_replace ("{order_total}",$payment_amount,$message_body);                         
        $subject = "Thanks for your order from Dog Treat House";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= "From: orders@dogtreathouse.com" . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail($payer_email,$subject,$message_body,$headers);
    
      }

     ///////////////////////////////////// END TRANSACTION VERIFIED FUNCTIONS
    } elseif (strcmp ($res, "INVALID") == 0) {
    // log for manual investigation
        //$res_txt =  strcmp($res, "VERIFIED");
        mail("tony@tonycalhoun.com","test22",$res,$headers);
    }
  }
  fclose ($fp);
}
?>
