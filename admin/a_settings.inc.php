          <h1>Settings</h1>
          Update the settings for the site and administrator account below.<br /><br />
          
          
                <form action="func.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="cmd" value="settings">
                
                  <table cellspacing="5" cellpadding="7" class="altRows">
                  <tr>
                    <td>Company Name<br /><input type="text" name="company" value="<?=$settings['company'];?>"></td>
                    <td>Contact Email<br /><input type="text" name="contact_email" value="<?=$settings['contact_email'];?>"></td>
                  </tr>
                  <tr>
                    <td>Orders Email:<br /><input type="text" name="orders_email" value="<?=$settings['orders_email'];?>"></td>
                    <td>Email From Name<br /><input type="text" name="email_from_name" value="<?=$settings['email_from_name'];?>"></td>
                    <td>Email From Address<br /><input type="text" name="email_from_address" value="<?=$settings['email_from_address'];?>"></td>
                  </tr>
                  <tr>
                    <td>Admin Username<br /><input type="text" name="user_name" value="<?=$admin_user['user_name'];?>"></td>
                    <td>Admin Password<br /><input type="text" name="password" value="<?=$admin_user['password'];?>"></td>
                  </tr>
                  <tr><td colspan="3">
                    <input type="submit" name="edit" value="Save Changes"> 
                  </td></tr>
                  </table>
                
                </form>