<?
session_start();
include("global.inc.php");

 $shipping = mysql_escape_string($_REQUEST['shipping']);

    $vars = array(
            'cmd' => '_cart',
            'upload' => '1',
            'business' => 'sawmillcreek.smokehouse@gmail.com',
            'lc' => 'GB',
            'notify_url' => 'http://www.dogtreathouse.com/DaEJszDa.php',
            'return' => 'http://www.dogtreathouse.com/thanks.php',
            'currency_code' => 'USD',
            'button_subtype' => 'goods',
            'no_note' => 0,
            'tax_rate' => 0,
    );
 
    // Add Items
    $i = 1;
    foreach($_SESSION['cart'] as $key => $value) {
        $item_name = ucwords($_SESSION['cart'][$key]['name']);
        $vars['item_name_'.$i]=$item_name;
        $vars['item_number_'.$i]=$key;

          $vars['amount_'.$i]= $_SESSION['cart'][$key]['price'] / $_SESSION['cart'][$key]['qty'];
          $vars['amount_'.$i] = number_format($vars['amount_'.$i],2);
          if ($_SERVER['REMOTE_ADDR']==$dev_ip) {
            $vars['amount_'.$i] = "0.01"; 
          }
          $opts_ary = explode("|",$_SESSION['cart'][$key]['options']);
          $opts_label = $opts_ary[0];
          $opts_price = $opts_ary[1];
          if ($opts_label) { $vars['item_name_'.$i]=$item_name." (".$opts_label.")"; }
          
        
        $vars['quantity_'.$i]=$_SESSION['cart'][$key]['qty'];      
        $total_price = $vars['amount_'.$i] * $vars['quantity_'.$i] + $total_price;  
        $order_detail .= $_SESSION['cart'][$key]['qty']." - ".$item_name." - $".number_format($_SESSION['cart'][$key]['price'],2)."<br />";
        $i++;
    } 
    
     if ($_SESSION['coupon']['unit']=="Percent") {
       $vars['discount_rate_cart']=$_SESSION['coupon']['amount'];
     } elseif ($_SESSION['coupon']['unit']=="Flat Amount") {
       $vars['discount_amount_cart']=$_SESSION['coupon']['amount'];
     }      


      if ($shipping=="5.00" && $total_price<35) {
        $vars['item_name_'.$i]="Flat Rate Shipping";
        $vars['item_number_'.$i]="shipflat";
        if ($_SERVER['REMOTE_ADDR']==$dev_ip) { 
          $vars['amount_'.$i]="0.01";  
        } else {
          $vars['amount_'.$i]="5.00";          
        }
        $vars['quantity_'.$i]=1;
      }    
    

    $ip = $_SERVER['REMOTE_ADDR'];
    mysql_query("INSERT INTO orders SET order_detail='$order_detail', order_total='$total_price', order_sh_total='$shipping', order_date=now(), ip='$ip'");
    $vars['custom']=mysql_insert_id();
    
        
 header('Location: https://www.paypal.com/cgi-bin/webscr?' . http_build_query($vars));
?>