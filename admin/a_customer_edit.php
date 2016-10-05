<? 
include("../global.inc.php");
include("header.php"); 
$ID = mysql_escape_string($_REQUEST['ID']);
$customer_sql = mysql_query("SELECT * FROM users WHERE ID='".$ID."'");
$customer_row = mysql_fetch_array($customer_sql);
?>

  
<div id="admin_dashboard">

    <form action="a_funcs.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="cmd" value="customer_edit">
    <input type="hidden" name="ID" value="<?=$ID?>">


    <h2>Updating Customer</h2>    
    
    <table cellspacing="5" cellpadding="5" id="customersTable">
              <tr>
                <td>First Name<br /><input type="text" name="first_name" value="<?=$customer_row['first_name'];?>"></td>
                <td>Last Name<br /><input type="text" name="last_name" value="<?=$customer_row['last_name'];?>"></td>
                <td>Phone<br /><input type="text" name="phone" value="<?=$customer_row['phone'];?>"></td>
                <td>Email<br /><input type="text" name="email" value="<?=$customer_row['email'];?>"></td>
              </tr>

              <tr><td>&nbsp;</td></tr>

              <tr>
                <td>Billing Address<br /><input type="text" name="billing_address" value="<?=$customer_row['billing_address'];?>"></td>
                <td>City<br /><input type="text" name="billing_city" value="<?=$customer_row['billing_city'];?>"></td>
                <td>State<br /><input type="text" name="billing_state" value="<?=$customer_row['billing_state'];?>"></td>
                <td>Zip<br /><input type="text" name="billing_zip" value="<?=$customer_row['billing_zip'];?>"></td>                    
              </tr>
              
              <tr><td>&nbsp;</td></tr>

              <tr>
                <td>Shipping Address<br /><input type="text" name="shipping_address" value="<?=$customer_row['shipping_address'];?>"></td>
                <td>City<br /><input type="text" name="shipping_city" value="<?=$customer_row['shipping_city'];?>"></td>
                <td>State<br /><input type="text" name="shipping_state" value="<?=$customer_row['shipping_state'];?>"></td>
                <td>Zip<br /><input type="text" name="shipping_zip" value="<?=$customer_row['shipping_zip'];?>"></td>                    
              </tr>
              
              <tr><td>&nbsp;</td></tr>
              
              <tr>
                <td>Username (optional)<br /><input type="text" name="user_name" value="<?=$customer_row['user_name'];?>"></td>
                <td>Password<br /><input type="text" name="password" value="<?=$customer_row['password'];?>"></td>
              </tr>
              
              <tr><td>&nbsp;</td></tr>
              
              <tr><td colspan="3">
                <input type="submit" name="edit" value="Add Customer" class="purpleButton"> &nbsp;&nbsp;&nbsp;&nbsp; <input type="button" class="purpleButton" onClick="location.href='a_customers.php'" value="Cancel">
              </td></tr>
    </table>
    
  
  </form>
    
  </div>
  
  <br style="clear: both;" /><br />
  
   
<? include("footer.php"); ?>
