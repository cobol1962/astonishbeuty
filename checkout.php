<?
session_start();
include("global.inc.php");
include("func.inc.php");
include("header.php");
include("top-nav.php");
$page='checkout';
include("init.php");
?>

<div class="main-holder">  
<div class="content">
<div class="inner_content">

    
  <div style="text-align: center;">
    <div class="title-holder"><h1>View Cart & Checkout</h1></div>
  </div><br style="clear: both;" /><br />

      <strong>Please review your order below and click "Pay Now" at the bottom to continue to payment page.</strong><br /><br />
      
      <? 
      if (count($_SESSION['cart'])<1) { 
      ?>
        <i>Shopping Cart is empty. <a href="jerky-dog-treats.php">Click Here to Continue Shopping.</a>
      <? } else { ?>
      	
      	<?php //echo '<pre>'; print_r($_SESSION); echo '</pre>';?>
          
              <form method="post">
      	      <input type="hidden" name="action" value="update_cart">
      	      <table id="cart" cellpadding="5" style="width: 80%;">
              <tr class="tdhdr"><td>Qty</td><td>Item</td><td>Each Price</td><td>Total Price</td><td style="width: 120px;">Remove</td></tr>
              <?
              foreach($_SESSION['cart'] as $key => $value) {
              ?>
      		<input type="hidden" name="skey[]" value="<?=ucwords($key);?>">
                <input type="hidden" name="ID[]" value="<?=ucwords($_SESSION['cart'][$key]['ID']);?>">
              <tr>
                <td><input type="text" name="qty[]" value="<?=$_SESSION['cart'][$key]['qty'];?>"  size="2"> <?/*<input type="submit" name="btn_update" value="update" class="lightButton2">*/?></td>  
                <td><?=ucwords($_SESSION['cart'][$key]['name']);?>
                <? 
                if ($_SESSION['cart'][$key]['options']) { 
                  $opt_ary = explode("|",$_SESSION['cart'][$key]['options']); 
                  echo "(".$opt_ary[0].")";
                } 
                ?>
               </td>
      	       <td>
                  $<?=number_format($_SESSION['cart'][$key]['eachprice'],2);?>
                </td>
                <td>
                  $<?=number_format($_SESSION['cart'][$key]['price'],2);?>
                </td>
                <td align="center"><a href="cart_del.php?ID=<?=$key?>"><i class="fa fa-close"></i></a></td>
              </tr>
              <?
               // Subtotal
               $total_price = number_format($_SESSION['cart'][$key]['price'] + $total_price,2);
                        
               // APPY Coupon
               if (is_array($_SESSION['coupon'])) { 
                 if ($_SESSION['coupon']['unit']=="Percent") {
                   $percent_decimal = $_SESSION['coupon']['amount'] / 100;
                   $discounted_price = number_format($percent_decimal * $total_price,2);
                   $grand_total = $total_price - $discounted_price;
                   $display_coupon = $_SESSION['coupon']['amount']."% OFF ($".$discounted_price.")";
                   $db_coupon = $_SESSION['coupon']['amount']."!@!".$_SESSION['coupon']['unit'];
                 } elseif ($_SESSION['coupon']['unit']=="Flat Amount") {
                   $discounted_price = number_format($_SESSION['coupon']['amount'],2);
                   $grand_total = $total_price - $discounted_price;
                   $display_coupon = "$".number_format($_SESSION['coupon']['amount'],2)." OFF";
                   $db_coupon = $_SESSION['coupon']['amount']."!@!".$_SESSION['coupon']['unit'];
                 }     
               } else {
                 $grand_total = $total_price;
               }
              }
              ?>
              <tr><td style="border-bottom: none;">&nbsp;</td></tr>
              </form>
      		
              <? if ($use_coupon_code=="1") { ?>
              <tr><td colspan="5" style="text-align: left; border-bottom: none; width:300px;">
                <? if (is_array($_SESSION['coupon'])) { ?>
                  <strong>Subtotal:</strong> $<?=$total_price;?><br />
                  <strong style="color: green; font-size: 10pt;">Coupon Applied! <?=$display_coupon;?> - <a href="coupon.php?del=1" style="color: #666666;">Remove</a>
                <? } else { ?>
                  <div style="text-align: right;">
                  <? if ($_GET['error']=="invalidcode") { ?><div style="color: red; font-size: 9pt;">Invalid Coupon Code.</div><? } ?>
                  <? if ($_GET['error']=="notavail" || $_GET['error']=="disabled") { ?><div style="color: red; font-size: 9pt;">Coupon not available.</div><? } ?>
                  <? if ($_GET['error']=="expired") { ?><div style="color: red; font-size: 9pt;">Coupon Expired.</div><? } ?>
                  <strong>Coupon Code:</strong> <form action="coupon.php" method="post" style="display: inline;"><input type="text" name="code" size="10"><input type="submit" value="APPLY"></form>      
                  </div>
                <? } ?>
              </td>
              </tr>
              <? } ?>
              <tr><td colspan="4" style="text-align: left; border-bottom: none; width:300px;"><strong>Order Total:</strong> $<?=$grand_total?></td></tr>
              
              <?
               if ($total_price>35) { $sh_total = "FREE"; }
               else { $sh_total = "5.00"; }
              ?>
              
              <tr><td colspan="4" style="text-align: left; border-bottom: none; width:300px;"><strong>Shipping & Handling:</strong> $<?=$sh_total?></td></tr>
              </table>
      	
          
          <br /><br />
          <table id="cart">
          <tr style="vertical-align: top;">
      	    <td style="width:150px;">    
              <form action="checkoutf.php" method="post">
              <input type="hidden" name="xfer_total_price" value="<?=$grand_total?>">
              <input type="hidden" name="shipping" value="<?=$sh_total?>">
              <input type="image" name="submit" border="0" src="images/button_checkout.png" alt="PayPal - The safer, easier way to pay online"> 
              </form>
        	  </td>
          <td style="width:20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <td style="text-align: right; width:150px;">
                <? 
                $frompg = $_SESSION['frompg'];
                if (!$frompg) { $frompg = "index.php"; }
                ?>
                <a href="<?=$frompg?>"><img src="images/button_keepshopping.png"></a>
              </td>
          </tr>
          </table>
      
      <? } ?>
      
<br /><br />

</div>
</div>
</div>

<div style="clear: left;"></div>

<? include("footer.php"); ?>