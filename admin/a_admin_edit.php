<? 
include("../global.inc.php");
include("header.php"); 
$ID = mysql_escape_string($_REQUEST['ID']);
$admin_sql = mysql_query("SELECT * FROM users WHERE ID='".$ID."'");
$admin_row = mysql_fetch_array($admin_sql);
?>

  
<div id="admin_dashboard">

    <form action="a_funcs.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="cmd" value="admin_edit">
    <input type="hidden" name="ID" value="<?=$ID?>">


    <h2>Updating Admin</h2>    
    
    <table cellspacing="5" cellpadding="5" id="adminsTable">
              <tr>
                <td>First Name<br /><input type="text" name="first_name" value="<?=$admin_row['first_name'];?>"></td>
                <td>Last Name<br /><input type="text" name="last_name" value="<?=$admin_row['last_name'];?>"></td>
                <td>Phone<br /><input type="text" name="phone" value="<?=$admin_row['phone'];?>"></td>
                <td>Email<br /><input type="text" name="email" value="<?=$admin_row['email'];?>"></td>
              </tr>

              <tr><td>&nbsp;</td></tr>

              <tr>
                <td>Billing Address<br /><input type="text" name="billing_address" value="<?=$admin_row['billing_address'];?>"></td>
                <td>City<br /><input type="text" name="billing_city" value="<?=$admin_row['billing_city'];?>"></td>
                <td>State<br /><input type="text" name="billing_state" value="<?=$admin_row['billing_state'];?>"></td>
                <td>Zip<br /><input type="text" name="billing_zip" value="<?=$admin_row['billing_zip'];?>"></td>                    
              </tr>
              
              <tr><td>&nbsp;</td></tr>

              <tr>
                <td>Shipping Address<br /><input type="text" name="shipping_address" value="<?=$admin_row['shipping_address'];?>"></td>
                <td>City<br /><input type="text" name="shipping_city" value="<?=$admin_row['shipping_city'];?>"></td>
                <td>State<br /><input type="text" name="shipping_state" value="<?=$admin_row['shipping_state'];?>"></td>
                <td>Zip<br /><input type="text" name="shipping_zip" value="<?=$admin_row['shipping_zip'];?>"></td>                    
              </tr>
              
              <tr><td>&nbsp;</td></tr>
              
              <tr>
                <td>Username (optional)<br /><input type="text" name="user_name" value="<?=$admin_row['user_name'];?>"></td>
                <td>Password<br /><input type="text" name="password" value="<?=$admin_row['password'];?>"></td>
              </tr>
              
              <tr><td>&nbsp;</td></tr>
              
              <tr><td colspan="3">
                <input type="submit" name="edit" value="Add Admin" class="purpleButton"> &nbsp;&nbsp;&nbsp;&nbsp; <input type="button" class="purpleButton" onClick="location.href='a_admins.php'" value="Cancel">
              </td></tr>
    </table>
    
  
  </form>
    
  </div>
  
  <br style="clear: both;" /><br />
  
   
<? include("footer.php"); ?>
