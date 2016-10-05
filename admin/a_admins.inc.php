


        <a href="#" class="toggle button" toggleDiv="admin_add"><i class="fa fa-plus"></i> Add New Contact</a>  &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="toggle button" toggleDiv="admin_filter"><i class="fa fa-filter"></i> Filters</a> 
        <br /><br />
                      
                      
                  <?
                  if ($_SESSION['admin_filters_keywords']) { 
                    $filters .= " AND (
                                  first_name like '%".$_SESSION['admin_filters_keywords']."%' OR last_name like '%".$_SESSION['admin_filters_keywords']."%' OR 
                                  state like '%".$_SESSION['admin_filters_keywords']."%' 
                                  OR city like '%".$_SESSION['admin_filters_keywords']."%' OR address like '%".$_SESSION['admin_filters_keywords']."%' 
                                  OR state like '%".$_SESSION['admin_filters_keywords']."%'
                                  OR email like '%".$_SESSION['admin_filters_keywords']."%'
                                  OR phone like '%".$_SESSION['admin_filters_keywords']."%'
                                  OR admin_name like '%".$_SESSION['admin_filters_keywords']."%'
                                  )"; 
                  }
                  $admin_sql = mysql_query("SELECT * FROM users WHERE user_type='admin' $filters");
                  $p_num = mysql_num_rows($admin_sql);
                  ?>
              
                  <? if ($p_num>0) { ?>
                    <table cellspacing="5" cellpadding="5" class="altRows" style="width: 100%;">
                    <tr class="tdHdr"><td>Name</td><td>Phone/Email</td><td>Last Action</td><td>Actions</td></tr>
                    <?
                    while ($admin_row = mysql_fetch_array($admin_sql)) {
                    $descr = $admin_row['description'];
                    $descr_max_len = 100;
                    if (strlen($descr)>$descr_max_len) { $descr = substrword($descr, $descr_max_len, "..."); }
                    ?>
                    
                      <tr>
                        <td style="min-width: 220px;">
                          <strong><?=ucwords($admin_row['first_name']);?> <?=ucwords($admin_row['last_name']);?></strong><br >
                          <? if ($admin_row['address']) { ?><?=$admin_row['address'];?><br /><?=$admin_row['city'];?>, <?=$admin_row['state']?> <?=$admin_row['zip']?><? } ?>
                        </td>
                        <td style="min-width: 220px;">
                          <?=$admin_row['phone'];?><br />
                          <a href="mailto:<?=$admin_row['email'];?>"><?=$admin_row['email'];?></a>
                        </td>
                        <td>
                          <?=date("m/d/y", strtotime($admin_row['last_action_date']));?><br />
                          <?=$admin_row['last_action_text'];?>
                        </td>
                        <td>
                          <a href="admin_edit.php?ID=<?=$admin_row['ID'];?>" class="button"><i class="fa fa-edit"></i> Edit</a>  <a href="func.php?cmd=admin_del&ID=<?=$admin_row['ID'];?>" class="button" onClick="return confirm('Deleete <?=$admin_row['name'];?>?');"><i class="fa fa-trash"></i> Delete</a>
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
                  
                    <? if ($_SESSION['admin_filters_search']) { ?>
                      <i>Your search turned up 0 results, please try again or <a href="func.php?cmd=admin_filters&clear=1">clear filters</a>.</i>
                    <? } else { ?>
                      <i>There are no admins to display at this time.</i>
                    <? } ?>
                  
                  <? } ?>
              
              
              <div id="admin_filter">
              
                <form action="funcs.php" method="post">
                <input type="hidden" name="cmd" value="admin_filters">
                <input type="hidden" name="search" value="1">
                <table><td>
                <td>Keywords:<br /><input type="text" name="keywords" size="20"></td>
                <td><br /><input type="submit" value="GO" name="submit"> <input type="submit" name="clear" value="Clear"></td>
                </tr></table>
                </form>
              
              </div>
              
              <div id="admin_add">
            
                <h2>Create New Contact</h2>
                
                <form action="funcs.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="cmd" value="admin_add">
                
                  <table cellspacing="5" cellpadding="5">
                  <tr>
                    <td>First Name<br /><input type="text" name="first_name" value="<?=$_SESSION['first_name'];?>"></td>
                    <td>Last Name<br /><input type="text" name="last_name" value="<?=$_SESSION['last_name'];?>"></td>
                  </tr>
                  <tr>
                    <td>Phone<br /><input type="text" name="phone" value="<?=$_SESSION['phone'];?>"></td>
                    <td>Email<br /><input type="text" name="email" value="<?=$_SESSION['email'];?>"></td>
                  </tr>
                  <tr>
                    <td>Address<br /><input type="text" name="address" value="<?=$_SESSION['address'];?>"></td>
                    <td>City<br /><input type="text" name="city" value="<?=$_SESSION['city'];?>"></td>
                  </tr>
                  <tr>
                    <td>State<br /><input type="text" name="state" value="<?=$_SESSION['state'];?>"></td>
                    <td>Zip<br /><input type="text" name="zip" value="<?=$_SESSION['zip'];?>"></td>                    
                  </tr>
                  <tr><td colspan="3">
                    <input type="submit" name="edit" value="Add Contact" class="greenButton"> &nbsp;&nbsp;&nbsp;&nbsp; <input type="button" class="toggle button" toggleDiv="admin_add" value="Cancel">
                  </td></tr>
                  </table>
                
                </form>
              
              </div>
              
              
              
              
              