<? 
// Dev: Tony Calhoun - tony@creativenetfx.com
//
// Required Vars:
// $act = Action to do
// 
// Optional Vars:
// $required_fields = Must be sent as an array (name="required_fields[]")
//

include("../global.inc.php");
include("../class.phpmailer.php");
include("../mailhost.inc.php");
include("../func.inc.php");

foreach($_REQUEST as $key => $value) {
  $_SESSION[$cmd."_".$key]=$value;
  $$key = $value;
  $dbVar = "db_".$key;
  if (!is_array($value)) { $$dbVar = mysql_escape_string($value); }
}

// Check required fields
unset($_SESSION['missingFields']);
if (is_array($required_fields)) { 
  foreach ($required_fields as $currReq) {
    if (!$_POST[$currReq]) { 
      $missingFields = 1;
      $_SESSION['missingFields'][]=$currReq;
    }
  }
}

  function uploadImage($ID){
      global $_FILES, $root, $product_images_dir, $thumbnail_size;
      $picture_temp = $_FILES['image']['tmp_name'];
      // Upload Image
      $picture_type = $_FILES['image']['type'];
      $picture_name = $ID.".jpg";
      $picture_thumb_name = $ID."_thumb.jpg";
      $temp_path = $root."/temp/".$picture_name;
      $picSize = getimagesize($picture_temp);
      $width = $picSize[0]; 
      $height = $picSize[1]; 
      $new_large_path = $root."/".$product_images_dir."/".$picture_name;
      $new_thumb_path = $root."/".$product_images_dir."/".$picture_thumb_name;
      move_uploaded_file($picture_temp,$temp_path);
      if ($height>$thumbnail_size) { $resize = "-resize ".$thumbnail_size."x"; }
      exec("/usr/bin/convert $temp_path $new_large_path"); // Large Path
      exec("/usr/bin/convert $new_large_path $resize $new_thumb_path");
  }
  
  
// Actions
switch($cmd) {

  case "login": 
  
    $login_sql = mysql_query("SELECT * FROM users where user_type='admin' AND (email='".$db_email."' OR user_name='".$db_email."') and password='$db_password'");
    $login_row = mysql_fetch_array($login_sql);
    $login_ID = $login_row['ID'];
    
      if ($login_ID) { 
        $_SESSION['admin_auth']="ok";
        header("location: a_dashboard.php");
      } else {
        header("location: a_login.php?error=1");
      }    

    break;
    

  case "forgotpw":     
  
    $forgot_sql = mysql_query("SELECT * FROM users where user_type='admin' AND (email='".$db_email."' OR user_name='".$db_email."') and password='$db_password'");
    $forgot_row = mysql_fetch_array($forgot_sql);
    $forgot_ID = $forgot_row['ID'];
    
    if ($forgot_ID) { 
      //Email to Order to Customer
      $message_body = file_get_contents("../email_forgotpw.html");
      $message_body = str_replace("{user_name}",$forgot_row['user_name'],$message_body);
      $message_body = str_replace("{email}",$forgot_row['email'],$message_body);
      $message_body = str_replace("{password}",$forgot_row['password'],$message_body);
      $mail->ClearAddresses();
      $mail->Subject = "Here's your login information for ".$settings["company"];
      $mail->Body    = $message_body;
      $mail->AddAddress($forgot_row['email']);
      $mail->Send();    
      header("location: a_login.php?chk=sent_pw");
    } else {
      header("location: a_login.php?error=not_found");
    }
    break;


  case "customer_add":

    if ($missingFields=="1") {
      header("location: a_customers.php?error=customer_add_fields");
    } else {
    
      mysql_query("INSERT INTO users SET 
      user_type='customer', email='$db_email', user_name='$db_user_name', 
      password='$db_password', first_name='$db_first_name', last_name='$db_last_name', phone='$db_phone', 
      billing_address='$db_billing_address', billing_city='$db_billing_city', billing_state='$db_billing_state', billing_zip='$db_billing_zip', 
      shipping_address='$db_shipping_address', shipping_city='$db_shipping_city', shipping_state='$db_shipping_state', shipping_zip='$db_shipping_zip'
      ") or die(mysql_error());
      $_SESSION['last_product']=$name;
      header("location: a_customers.php?chk=customer_added");    
    }
    exit;
    break;

  case "customer_edit":

    if ($missingFields=="1") {
      header("location: a_customers.php?error=customer_edit_fields");
    } else {
      mysql_query("UPDATE users SET 
        user_type='customer', email='$db_email', user_name='$db_user_name', 
        password='$db_password', first_name='$db_first_name', last_name='$db_last_name', phone='$db_phone', 
        billing_address='$db_billing_address', billing_city='$db_billing_city', billing_state='$db_billing_state', billing_zip='$db_billing_zip', 
        shipping_address='$db_shipping_address', shipping_city='$db_shipping_city', shipping_state='$db_shipping_state', shipping_zip='$db_shipping_zip'
        where ID='$ID'
      ") or die(mysql_error());
      header("location: a_customers.php?chk=customer_edited");    
    }
    exit;
    break;
 
  case "customer_del":
    mysql_query("delete from users WHERE ID='$ID'");
    header("location: a_customers.php?chk=customer_deleted");    
    exit;
    break;
    
    
  case "admin_add":

    if ($missingFields=="1") {
      header("location: a_admins.php?error=admin_add_fields");
    } else {
    
      mysql_query("INSERT INTO users SET 
        user_type='admin', email='$db_email', user_name='$db_user_name', 
        password='$db_password', first_name='$db_first_name', last_name='$db_last_name'
      ") or die(mysql_error());
      $_SESSION['last_product']=$name;
      header("location: a_admins.php?chk=admin_added");    
    }
    exit;
    break;

  case "admin_edit":

    if ($missingFields=="1") {
      header("location: a_admins.php?error=customer_edit_fields");
    } else {
      mysql_query("UPDATE users SET 
        user_type='admin', email='$db_email', user_name='$db_user_name', 
        password='$db_password', first_name='$db_first_name', last_name='$db_last_name'
        where ID='$ID'
      ") or die(mysql_error());
      header("location: a_admins.php?chk=admin_edited");    
    }
    exit;
    break;
 
  case "admin_del":
    mysql_query("delete from users WHERE ID='$ID'");
    header("location: a_admins.php?chk=admin_deleted");    
    exit;
    break;
        
  case "category_add":

    if ($missingFields=="1") {
      header("location: admin_categories.php?error=1");
    } else {
      mysql_query("insert into product_cats SET name='$db_name'") or die(mysql_error());
      header("location: a_categories.php?chk=1");    
    }
    exit;
    break;
    
  case "category_edit":
  
     $query = mysql_query("select * from product_cats");
     while($info = mysql_fetch_array($query)) {
      $currID = $info['ID'];
      $currName = mysql_escape_string($_POST['name_'.$currID]);
      mysql_query("UPDATE product_cats SET name='".$currName."' WHERE ID='".$currID."'");
     }

     header("location: a_categories.php?chk=2");
           
    exit;
    break;


  case "category_del":
    mysql_query("DELETE FROM product_cats WHERE ID='".$ID."'");
    header("location: a_categories.php?chk=3");        
    exit;
    break;

  case "coupon_add":

    if ($missingFields=="1") {
      header("location: admin_coupons.php?error=1");
    } else {
      $db_starts = strtotime($db_starts);
      $db_expires = strtotime($db_expires);    
      mysql_query("insert into coupons SET name='$db_name', code='$db_code', unit='$db_unit', amount='$db_amount', status='$db_status', starts='$db_starts', expires='$db_expires'") or die(mysql_error());
      header("location: admin_coupons.php?chk=1");    
    }
    exit;
    break;
    
  case "coupon_edit":
  
     $query = mysql_query("select * from coupons");
     while($info = mysql_fetch_array($query)) {
      $currID = $info['ID'];
      $currName = mysql_escape_string($_POST['name_'.$currID]);
      $currCode = mysql_escape_string($_POST['code_'.$currID]);
      $currUnit = mysql_escape_string($_POST['unit_'.$currID]);
      $currAmount = mysql_escape_string($_POST['amount_'.$currID]);
      $currStatus = mysql_escape_string($_POST['status_'.$currID]);
      $currStarts = mysql_escape_string($_POST['starts_'.$currID]);
      $currExpires = mysql_escape_string($_POST['expires_'.$currID]);            
      $db_starts = strtotime($currStarts);
      $db_expires = strtotime($currExpires);
      mysql_query("UPDATE coupons SET name='$currName', code='$currCode', unit='$currUnit', amount='$currAmount', status='$currStatus', starts='$db_starts', expires='$db_expires' WHERE ID='".$currID."'");
     }

     header("location: admin_coupons.php?chk=2");
           
    exit;
    break;

  case "coupon_del":
    mysql_query("DELETE FROM coupons WHERE ID='".$ID."'");
    header("location: admin_coupons.php?chk=3");        
    exit;
    break;
        
  case "order_filters":

    if ($clear) { unset($_SESSION["order_filters_search"]); unset($_SESSION["order_filters_keywords"]); }
    header("location: a_orders.php?chk=order_filters");
    break;
        
  case "page_edit":

    if ($ID) {
      $body = htmlentities($body);
      $body2 = htmlentities($body2);
      mysql_query("update pages set title='$title', body='$body', body2='$body2' WHERE ID='$ID'");
    }
    header("location: a_page_edit.php?ID=$ID&chk=page_edited");
    break;
    
  case "settings":

    mysql_query("UPDATE settings SET company='$db_company', contact_email='$db_contact_email', orders_email='$db_orders_email', email_from_name='$db_email_from_name', email_from_address='$db_email_from_address' WHERE ID='1'");
    mysql_query("UPDATE users SET user_name='$db_user_name', email='$db_contact_email', password='$db_password' WHERE user_type='admin'");
    
    header("location: admin_index.php?chk=filters#settings");
    break;


  case "product_add":

    if ($missingFields=="1") {
      header("location: a_product.php?cat=$cat&error=1");
    } else {
      mysql_query("insert into products set 
      name='$db_name', price='$db_price', 
      category='$db_category', options='$db_options', description='$db_description', 
      description_full='$db_description_full', featured='$featured', date_added=now() 
      ") or die(mysql_error());
      $ID = mysql_insert_id();

      if ($_FILES['image']['tmp_name']) { 
        uploadImage($ID);
      } 
      
      foreach($_SESSION as $key => $value) { if (substr($key,0,4)=="asf_") { unset($_SESSION[$key]); } }
      header("location: a_products.php?cat=$category&chk=1");    
    }
    exit;
    break;

  case "product_edit":
  
   if ($missingFields=="1") {
      header("location: a_product.php?cat=$category&ID=$ID&error=1");
    } else {
      if ($_FILES['image']['tmp_name']) { uploadImage($ID); }
      mysql_query("update products set 
      name='$db_name', price='$db_price', 
      category='$db_category', options='$db_options', description='$db_description', 
      description_full='$db_description_full', featured='$featured', date_added=now() 
      WHERE ID='$ID'");
      
      
      $images=$_FILES['images'];
      for($i=0;$i<4;$i++){
        if(isset($_FILES['images'.$i]['name'])) {
	  if($_FILES['images'.$i]['name']<>''){
              $pics_sqss = mysql_query("DELETE FROM product_pics WHERE product_ID=".$ID." AND imgcount=".($i+1));	
              $ext=substr(strrchr($_FILES['images'.$i]['name'],"."),1);
              $ext=strtolower($ext);	
              $picture_temp = $_FILES['images'.$i]['tmp_name'];
              $pic = $_FILES['images'.$i]['name'];
              $picture_name= $_FILES['images'.$i]['name'];
              //Ensure Unique Filename
              $randNum = 1;
				  
	      if(file_exists($root."/".$product_images_dir."/".$pic))
              { 
                $randNum++;
                $ext = end(explode(".", $picture_name));
                $ext=substr(strrchr($_FILES['images'.$i]['name'],"."),1);
                $ext=strtolower($ext);	
                $filename = str_replace(".".$ext," ",$picture_name);					
                $filename = str_replace(" ","",$filename);
                $picture_name = $filename."_".$randNum.".".$ext;
              }else{
                $ext = end(explode(".", $picture_name));
                $ext=substr(strrchr($_FILES['images'.$i]['name'],"."),1);
                $ext=strtolower($ext);	
                $filename = str_replace(".".$ext," ",$picture_name);
                $filename = str_replace(" ","",$filename);
                $picture_name = $filename."_".$randNum.".".$ext;
              }		
				   
              // Upload Image
              $temp_path = $root."/temp/".$picture_name;
              
              $new_large_path = $root."/".$product_images_dir."/".$picture_name;
              $new_thumb_path = $root."/".$product_images_dir."/".$picture_thumb_name;
              move_uploaded_file($_FILES['images'.$i]['tmp_name'],$temp_path);
              if ($height>$thumbnail_size) { $resize = "-resize ".$thumbnail_size."x"; }
              exec("/usr/bin/convert $temp_path $new_large_path"); // Large Path
              exec("/usr/bin/convert $new_large_path $resize $new_thumb_path");
	      $select_img=mysql_query("SELECT MAX(imgcount) as imgcount FROM product_pics WHERE product_ID=".$ID);
	      $select_img_row = mysql_fetch_row($select_img);
	      mysql_query("INSERT INTO product_pics SET product_ID='".$ID."', pic='".$picture_name."',imgcount='".(int)($select_img_row['imgcount']+1)."'");
          }
        }	
      }

      header("location: a_products.php?cat=$category&chk=2");    
    }
    exit;
    break;
 
  case "product_del":
  
    mysql_query("DELETE FROM products WHERE ID='$ID'");
    header("location: a_products.php?cat=$category&chk=3");    
    
    exit;
    break;

  case "product_addimg":

    foreach($images as $currImg) {

        if ($currImg['tmp_name']) { 
        
          $picture_temp = $currImg['image']['tmp_name'];
          $picture_name = $currImg['image']['name'];
          // Upload Image
          $temp_path = $root."/temp/".$picture_name;
          $picSize = getimagesize($picture_temp);
          $height = $picSize[1]; 
          $new_large_path = $root."/".$product_images_dir."/".$picture_name;
          $new_thumb_path = $root."/".$product_images_dir."/".$picture_thumb_name;
          move_uploaded_file($picture_temp,$temp_path);
          if ($height>$thumbnail_size) { $resize = "-resize ".$thumbnail_size."x"; }
          exec("/usr/bin/convert $temp_path $new_large_path"); // Large Path
          exec("/usr/bin/convert $new_large_path $resize $new_thumb_path");
          mysql_query("INSERT INTO product_pics SET product_ID='".$ID."', pic='".$picture_name."'");
        
        }
    
    }

    header("location: a_product.php?ID=$ID&chk=5");
    break;
  

  case "product_dimg":

    mysql_query("update products set image='' where ID='$ID'");
    if (file_exists("products/".$ID.".jpg")) { unlink("products/".$ID.".jpg"); }
    if (file_exists("products/".$ID."_thumb.jpg")) { unlink("products/".$ID."_thumb.jpg"); }
    header("location: a_product.php?ID=$ID&chk=4");
    exit;
    break;
    
    

}
?>