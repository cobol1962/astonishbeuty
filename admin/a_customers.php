<? 
include("../global.inc.php");
include("a_auth.php");
include("header.php");
?>

<style>
#customer_add { display: none; }
#customer_filter { display: none; }
</style>


<h1>Customers</h1>

    <a href="#" class="toggle basicButton" toggleDiv="customer_add"><i class="fa fa-plus"></i> Add New Customer</a>  &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="toggle basicButton" toggleDiv="customer_filter"><i class="fa fa-filter"></i> Filters</a> 
    <br /><br />
                  
                  
  <? if ($_GET['chk']=="customer_edited") { ?><div class="chk">Customer has been modified.</div><? } ?>
                  
              <?
              if ($_SESSION['customer_filters_keywords']) { 
                $filters .= " AND (
                              first_name like '%".$_SESSION['customer_filters_keywords']."%' OR last_name like '%".$_SESSION['customer_filters_keywords']."%' OR 
                              state like '%".$_SESSION['customer_filters_keywords']."%' 
                              OR city like '%".$_SESSION['customer_filters_keywords']."%' OR address like '%".$_SESSION['customer_filters_keywords']."%' 
                              OR state like '%".$_SESSION['customer_filters_keywords']."%'
                              OR email like '%".$_SESSION['customer_filters_keywords']."%'
                              OR phone like '%".$_SESSION['customer_filters_keywords']."%'
                              )"; 
              }
              $customer_sql = mysql_query("SELECT * FROM users WHERE user_type='customer' $filters");
              $p_num = mysql_num_rows($customer_sql);
              ?>
          
              <? if ($p_num>0) { ?>
                <table cellspacing="5" cellpadding="5" class="altRows" style="width: 100%;" id="adminTable">
                <tr class="tdHdr"><td>Name</td><td>Phone/Email</td><td>Orders</td><td>Actions</td></tr>
                <?
                while ($customer_row = mysql_fetch_array($customer_sql)) {
                $descr = $customer_row['description'];
                $descr_max_len = 100;
                if (strlen($descr)>$descr_max_len) { $descr = substrword($descr, $descr_max_len, "..."); }
                ?>
                
                  <tr>
                    <td style="min-width: 220px;">
                      <strong><?=ucwords($customer_row['first_name']);?> <?=ucwords($customer_row['last_name']);?></strong><br >
                      <? if ($customer_row['address']) { ?><?=$customer_row['address'];?><br /><?=$customer_row['city'];?>, <?=$customer_row['state']?> <?=$customer_row['zip']?><? } ?>
                    </td>
                    <td style="min-width: 220px;">
                      <?=$customer_row['phone'];?><br />
                      <a href="mailto:<?=$customer_row['email'];?>"><?=$customer_row['email'];?></a>
                    </td>
                    <td>
                      <?=$customer_row['num_orders'];?>
                    </td>
                    <td>
                      <a href="a_customer_edit.php?ID=<?=$customer_row['ID'];?>" class="button"><i class="fa fa-edit"></i> Edit</a>  <a href="a_funcs.php?cmd=customer_del&ID=<?=$customer_row['ID'];?>" class="button" onClick="return confirm('Delete <?=$customer_row['name'];?>?');"><i class="fa fa-trash"></i> Delete</a>
                    </td>
                  </tr>
                
                <?
                }
                ?>
                <tr><td colspan="3" style="display: none;">
                  <input type="submit" name="edit" value="Delete Checked">
                </td></tr>
                </table>
              <? } else { ?>
              
                <? if ($_SESSION['customer_filters_search']) { ?>
                  <i>Your search turned up 0 results, please try again or <a href="admin_funcs.php?cmd=customer_filters&clear=1">clear filters</a>.</i>
                <? } else { ?>
                  <i>There are no customers to display at this time.</i>
                <? } ?>
              
              <? } ?>
          
          
          <div id="customer_filter">
          
            <form action="a_funcs.php" method="post">
            <input type="hidden" name="cmd" value="customer_filters">
            <input type="hidden" name="search" value="1">
            <table><tr>
            <td>Keywords:<br /><input type="text" name="keywords" size="20"></td>
            <td><br /><input type="submit" value="GO" name="submit"> <input type="submit" name="clear" value="Clear"></td>
            </tr></table>
            </form>
          
          </div>
          
          <div id="customer_add">
        
            <h2>Add New Customer</h2>
            
            <form action="a_funcs.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="cmd" value="customer_add">
            
              <table cellspacing="2" cellpadding="0">

              <tr>
                <td>First Name<br /><input type="text" name="first_name" value="<?=$_SESSION['first_name'];?>"></td>
                <td>Last Name<br /><input type="text" name="last_name" value="<?=$_SESSION['last_name'];?>"></td>
                <td>Phone<br /><input type="text" name="phone" value="<?=$_SESSION['phone'];?>"></td>
                <td>Email<br /><input type="text" name="email" value="<?=$_SESSION['email'];?>"></td>
              </tr>

              <tr><td>&nbsp;</td></tr>

              <tr>
                <td>Billing Address<br /><input type="text" name="billing_address" value="<?=$_SESSION['billing_address'];?>"></td>
                <td>City<br /><input type="text" name="billing_city" value="<?=$_SESSION['billing_city'];?>"></td>
                <td>State<br /><input type="text" name="billing_state" value="<?=$_SESSION['billing_state'];?>"></td>
                <td>Zip<br /><input type="text" name="billing_zip" value="<?=$_SESSION['billing_zip'];?>"></td>                    
              </tr>
              
              <tr><td>&nbsp;</td></tr>

              <tr>
                <td>Shipping Address<br /><input type="text" name="shipping_address" value="<?=$_SESSION['shipping_address'];?>"></td>
                <td>City<br /><input type="text" name="shipping_city" value="<?=$_SESSION['shipping_city'];?>"></td>
                <td>State<br /><input type="text" name="shipping_state" value="<?=$_SESSION['shipping_state'];?>"></td>
                <td>Zip<br /><input type="text" name="shipping_zip" value="<?=$_SESSION['shipping_zip'];?>"></td>                    
              </tr>
              
              <tr><td>&nbsp;</td></tr>
              
              <tr>
                <td>Username (optional)<br /><input type="text" name="user_name" value="<?=$_SESSION['user_name'];?>"></td>
                <td>Password<br /><input type="text" name="password" value="<?=$_SESSION['password'];?>"></td>
              </tr>
              
              <tr><td>&nbsp;</td></tr>
              
              <tr><td colspan="3">
                <input type="submit" name="edit" value="Add Customer" class="purpleButton"> &nbsp;&nbsp;&nbsp;&nbsp; <input type="button" class="toggle purpleButton" toggleDiv="customer_add" value="Cancel">
              </td></tr>
              </table>
            
            </form>
          
          </div>
          

                        


<? include("footer.php");?>