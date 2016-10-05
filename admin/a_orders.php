<? 
include("../global.inc.php");
include("a_auth.php");
include("header.php");
?>

<style>
#order_add { display: none; }
#order_filter { display: none; }
</style>

<div id="admin_dashboard">

<h1>Orders</h1>

    <a href="#" class="toggle basicButton" toggleDiv="filter_box"><i class="fa fa-filter"></i> Filters</a> 
    <br /><br />
                  
                  
              <?
              if ($_SESSION['order_filters_keywords']) { 
                $filters .= " AND (
                              first_name like '%".$_SESSION['order_filters_keywords']."%' 
                              OR last_name like '%".$_SESSION['order_filters_keywords']."%' 
                              OR billing_state like '%".$_SESSION['order_filters_keywords']."%' 
                              OR billing_city like '%".$_SESSION['order_filters_keywords']."%' 
                              OR billing_state like '%".$_SESSION['order_filters_keywords']."%'
                              OR email like '%".$_SESSION['order_filters_keywords']."%'
                              OR phone like '%".$_SESSION['order_filters_keywords']."%'
                              )"; 
              }
              $order_sql = mysql_query("SELECT * FROM orders WHERE ID!='' $filters");
              $p_num = mysql_num_rows($order_sql);
              ?>
          
              <? if ($p_num>0) { ?>
                <table cellspacing="5" cellpadding="5" class="altRows" style="width: 100%;" id="adminTable">
                <tr class="tdHdr"><td>Date</td><td>Customer</td><td>Details</td><td>Actions</td></tr>
                <?
                while ($order_row = mysql_fetch_array($order_sql)) {
                ?>
                
                  <tr>
                    <td><?=date("M n, Y", strtotime($order_row['order_date']));?></td>
                    <td style="min-width: 220px;">
                      <strong><?=ucwords($order_row['first_name']);?> <?=ucwords($order_row['last_name']);?></strong><br >
                      <? if ($order_row['address']) { ?><?=$order_row['address'];?><br /><?=$order_row['city'];?>, <?=$order_row['state']?> <?=$order_row['zip']?><? } ?>
                    </td>
                    <td>
                      <?=$order_row['order_detail'];?>
                    </td>
                    <td>
                      <a href="a_order_edit.php?ID=<?=$order_row['ID'];?>" class="button"><i class="fa fa-edit"></i> Edit</a>  <a href="a_funcs.php?cmd=order_del&ID=<?=$order_row['ID'];?>" class="button" onClick="return confirm('Delete <?=$order_row['name'];?>?');"><i class="fa fa-trash"></i> Delete</a>
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
              
                <? if ($_SESSION['order_filters_search']) { ?>
                  <i>Your search turned up 0 results, please try again or <a href="a_funcs.php?cmd=order_filters&clear=1">clear filters</a>.</i>
                <? } else { ?>
                  <i>There are no orders to display at this time.</i>
                <? } ?>
              
              <? } ?>
          
          
          <div id="filter_box">
          
            <form action="a_funcs.php" method="post">
            <input type="hidden" name="cmd" value="order_filters">
            <input type="hidden" name="search" value="1">
            <table><tr>
            <td>Keywords:<br /><input type="text" name="keywords" size="20"></td>
            <td><br /><input type="submit" value="GO" name="submit"> <input type="submit" name="clear" value="Clear"></td>
            </tr></table>
            </form>
          
          </div>
          

</div>

                        


<? include("footer.php");?>