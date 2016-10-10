<? 
include("../global.inc.php");
include("a_auth.php");
include("header.php");
include("../func.inc.php");

$customers_ct = countRows("users","user_type='customer'");
$orders_ct = countRows("orders","ID!=''");
$customers_repeat_ct = countRows("users","user_type='customer' AND num_orders > 1");
?>
   
<style>
#admin_dashboard { width: 90%; margin: 0 auto; }
.admin_module { background: #2b377c; padding: 10px; width: 100%; }
.admin_module .hdr { background: #19246a; padding: 10px; font-size: 19pt; font-weight: bold; color: #fff; }
.admin_module .inner { width: 95%; margin: 0 auto; }
#admin_dashboard #left { width: 40%; float: left; margin-right: 30px; line-height: 18pt; }
#admin_dashboard #right { width: 40%; float: left;  line-height: 18pt; }
.ct { font-size: 21pt; }
a { font-size: 15pt; }
a:hover { color: #000; text-decoration: underline; }
</style>
    

<div id="admin_dashboard">

  <div id="left" style="line-height: 22pt;">
   
   <h1>Manage</h1>
   
   <ul>
   <li><a href="a_orders.php">Orders</a></li>
   <li><a href="a_customers.php">Customers</a></li>   
   <li><a href="a_products.php">Products</a></li>   
   <li><a href="a_admins.php">Admins</a></li>   
   <li><a href="a_page_edit.php?ID=1">Edit Home Page</a></li>
   <li><a href="a_page_edit.php?ID=2">Edit About Page</a></li>      
   <li><a href="a_page_edit.php?ID=3">Edit Contact Page</a></li>
	<li><a target="_blank" href="http://www.astonishingbeautyshop.com/ckeditor/filemanager/browser/custom/browser.html?Type=Image&Folder=images/home_slider">Edit slider on home page</a></li>
   </ul>       
   <h1>
      
  </div>
  
  
  <div id="right">

  <h1>Recent Orders</h1>
  (recent orders will be shown here)<br /><br />
  
  <h1>Quick Stats</h1>
  <span class="ct"><?=$customers_ct?></span> <strong>Customers</strong><br />
  <span class="ct"><?=$customers_repeat_ct?></span> <strong>Repeat Customers</strong><br />  
  <span class="ct"><?=$orders_ct?></span> <strong>Orders</strong><br />
  
  
  &nbsp;
  </div>


</div>

                        


<? include("footer.php");?>