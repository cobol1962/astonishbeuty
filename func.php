<? 
// Dev: Tony Calhoun - tony@creativenetfx.com
//
// Required Vars:
// $act = Action to do
// 
// Optional Vars:
// $required_fields = Must be sent as an array (name="required_fields[]")
//

include("global.inc.php");
include("class.phpmailer.php");
include("mailhost.inc.php");
include("func.inc.php");

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
  
      if ($_POST['password']=="pettreats") { 
        $_SESSION['admin_auth']="ok";
        header("location: admin_index.php");
      } else {
        header("location: admin_login.php?error=1");
      }    exit;
    break;

  case "forgotpw":     
  
    $forgot_sql = mysql_query("SELECT * FROM users where email='".$db_email."' OR user_name='".$db_email."'");
    $forgot_row = mysql_fetch_array($forgot_sql);
    $forgot_ID = $forgot_row['ID'];
    
    if ($forgot_ID) { 
      //Email to Order to Customer
      $message_body = file_get_contents("email_forgotpw.html");
      $message_body = str_replace("{user_name}",$forgot_row['user_name'],$message_body);
      $message_body = str_replace("{email}",$forgot_row['email'],$message_body);
      $message_body = str_replace("{password}",$forgot_row['password'],$message_body);
      $mail->ClearAddresses();
      $mail->Subject = "Here's your login information for ".$settings["company"];
      $mail->Body    = $message_body;
      $mail->AddAddress($forgot_row['email']);
      $mail->Send();    
      header("location: index.php?chk=sent_pw");
    } else {
      header("location: index.php?error=not_found");
    }
    break;
        
  case "category_add":

    if ($missingFields=="1") {
      header("location: admin_categories.php?error=1");
    } else {
      mysql_query("insert into product_cats SET name='$db_name'") or die(mysql_error());
      header("location: admin_categories.php?chk=1");    
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

     header("location: admin_categories.php?chk=2");
           
    exit;
    break;


  case "category_del":
    mysql_query("DELETE FROM product_cats WHERE ID='".$ID."'");
    header("location: admin_categories.php?chk=3");        
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
    header("location: admin_index.php?chk=filters#orders");
    break;
    

        
  case "page_edit":

    if ($ID) {
      mysql_query("update pages set name='$name', body='$body' WHERE ID='$ID'");
    }
    header("location: admin_index.php?chk=filters#orders");
    break;
    
       
  case "settings":

    mysql_query("UPDATE settings SET company='$db_company', contact_email='$db_contact_email', orders_email='$db_orders_email', email_from_name='$db_email_from_name', email_from_address='$db_email_from_address' WHERE ID='1'");
    mysql_query("UPDATE users SET user_name='$db_user_name', email='$db_contact_email', password='$db_password' WHERE user_type='admin'");
    
    header("location: admin_index.php?chk=filters#settings");
    break;


  case "product_add":

    if ($missingFields=="1") {
      header("location: admin_product.php?cat=$cat&error=1");
    } else {
      mysql_query("insert into products set name='$db_name', price='$db_price', 
      category='$db_category', sub_category='$db_sub_category', options='$db_options', description='$db_description', 
      ingredients='$db_ingredients', description_full='$db_description_full', health_allergy_notes='$db_health_allergy_notes', 
      legal_notes='$db_legal_notes', guaranteed_analysis='$db_guaranteed_analysis', 
      featured='$featured', date_added=now() 
      ") or die(mysql_error());
      $ID = mysql_insert_id();

      if ($_FILES['image']['tmp_name']) { 
        uploadImage($ID);
      } 
      
      foreach($_SESSION as $key => $value) { if (substr($key,0,4)=="asf_") { unset($_SESSION[$key]); } }
      header("location: admin_products.php?cat=$category&chk=1");    
    }
    exit;
    break;

  case "product_editws":
  
     $query = mysql_query("select * from products where category='Wholesale'");
     while($info = mysql_fetch_array($query)) {
      $currID = $info['ID'];
      $currUpdatePrice = $_POST['price_'.$currID];
      $currUpdateMinQty = $_POST['minqty_'.$currID];
      mysql_query("UPDATE products SET price='$currUpdatePrice' WHERE ID='$currID'");
     }
     
     $body = htmlentities(stripslashes($_POST['body']), ENT_QUOTES );
     mysql_query("UPDATE pages set body='$body' WHERE ID='5'");     
     header("location: admin_wsproducts.php");
  
    exit;
    break;

  case "product_edit":
  
   if ($missingFields=="1") {
      header("location: admin_product.php?cat=$category&ID=$ID&error=1");
    } else {
      if ($_FILES['image']['tmp_name']) { uploadImage($ID); }
      mysql_query("update products set 
      name='$db_name', price='$db_price', sub_category='$db_sub_category', category='$db_category', options='$db_options', 
      ingredients='$db_ingredients', description_full='$db_description_full', health_allergy_notes='$db_health_allergy_notes', 
      legal_notes='$db_legal_notes', guaranteed_analysis='$db_guaranteed_analysis', 
      description='$db_description', featured='$featured', date_added=now() 
      WHERE ID='$ID'");
      foreach($_SESSION as $key => $value) { if (substr($key,0,4)=="asf_") { unset($_SESSION[$key]); } }

			$images=$_FILES['images'];
        	print_r($images);
           // foreach($images as $ikey => $currImg) {
		   		for($i=0;$i<4;$i++){/*
        		//print_r($currImg);
               // if ($currImg['tmp_name']) { 
				//  echo $currImg;
				 
                  $picture_temp = $images['tmp_name'][$i];
                   $pic = $images['name'][$i];
                  //exit;
                  //Ensure Unique Filename
                  $randNum = 1;
                  while (file_exists($root."/".$product_images_dir."/".$pic)) {
                    $ext = end(explode(".", $picture_name));
                    $filename = str_replace(".".$ext,"",$picture_name);
                    $picture_name = $filename."_".$randNum.".".$ext;
                    $randNum++;
                  }
                        

                  // Upload Image
                  $temp_path = $root."/temp/".$picture_name;
                  $picSize = getimagesize($picture_temp);
                  $height = $picSize[1]; 
                  echo $new_large_path = $root."/".$product_images_dir."/".$picture_name;
                  $new_thumb_path = $root."/".$product_images_dir."/".$picture_thumb_name;
                  move_uploaded_file($picture_temp,$temp_path);
                  if ($height>$thumbnail_size) { $resize = "-resize ".$thumbnail_size."x"; }
                  exec("/usr/bin/convert $temp_path $new_large_path"); // Large Path
                  exec("/usr/bin/convert $new_large_path $resize $new_thumb_path");
				  echo "INSERT INTO product_pics SET product_ID='".$ID."', pic='".$picture_name."'";
				  exit;
                  mysql_query("INSERT INTO product_pics SET product_ID='".$ID."', pic='".$picture_name."'");
                
                //}
            
            */}
			
			/*echo '<pre>';
			  print_r($_FILES);
			echo '</pre>';
			exit;*/
			//if(isset($_FILES)){
			
			  		  
			
		for($i=0;$i<4;$i++){
		//echo '<br />';
				if(isset($_FILES['images'.$i]['name'])) {
				  if($_FILES['images'.$i]['name']<>''){
					//$select_img=mysql_query("SELECT * FROM product_pics WHERE product_ID=".$ID." AND imgcount=".$i);
					//$select_img_row = mysql_fetch_row($select_img);
					//if($select_img_row)
					$pics_sqss = mysql_query("DELETE FROM product_pics WHERE product_ID=".$ID." AND imgcount=".($i+1));	

					$ext=substr(strrchr($_FILES['images'.$i]['name'],"."),1);

					$ext=strtolower($ext);	
					
					$picture_temp = $_FILES['images'.$i]['tmp_name'];
                    $pic = $_FILES['images'.$i]['name'];
					$picture_name= $_FILES['images'.$i]['name'];
                 
                  //Ensure Unique Filename
                  $randNum = 1;
				   
				  
				   if(file_exists($root."/".$product_images_dir."/".$pic))
				   { $randNum++;
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
				   
				  // echo $picture_name;
				   
				   //exit;
				  
                 /* while (file_exists($root."/".$product_images_dir."/".$pic)) {
                    $ext = end(explode(".", $picture_name));
                    $filename = str_replace(".".$ext,"",$picture_name);					
					$filename = str_replace(" ","",$picture_name);
                    $picture_name = $filename."_".$randNum.".".$ext;
                    $randNum++;
                  }*/
               //$picture_name=$_FILES['images'.$i]['name'];         

                  // Upload Image
                  $temp_path = $root."/temp/".$picture_name;
                  //$picSize = getimagesize($picture_temp);
                  //$height = $picSize[1]; 
                   $new_large_path = $root."/".$product_images_dir."/".$picture_name;
                  $new_thumb_path = $root."/".$product_images_dir."/".$picture_thumb_name;
                  move_uploaded_file($_FILES['images'.$i]['tmp_name'],$temp_path);
                  if ($height>$thumbnail_size) { $resize = "-resize ".$thumbnail_size."x"; }
                  exec("/usr/bin/convert $temp_path $new_large_path"); // Large Path
                  exec("/usr/bin/convert $new_large_path $resize $new_thumb_path");
				  //echo "INSERT INTO product_pics SET product_ID='".$ID."', pic='".$picture_name."',imgcount='".(int)($i+1)."'";
				 // exit;
				 $select_img=mysql_query("SELECT MAX(imgcount) as imgcount FROM product_pics WHERE product_ID=".$ID);
				 $select_img_row = mysql_fetch_row($select_img);
				 
                  mysql_query("INSERT INTO product_pics SET product_ID='".$ID."', pic='".$picture_name."',imgcount='".(int)($select_img_row['imgcount']+1)."'");
				}
				}	
		}
	//}		


      header("location: admin_products.php?cat=$category&chk=2");    
    }
    exit;
    break;
 
  case "product_del":
    /*
    mysql_query("DELETE FROM products where ID='$ID'");
    if (file_exists("products/".$ID.".jpg")) { unlink("products/".$ID.".jpg"); }
    if (file_exists("products/".$ID."_thumb.jpg")) { unlink("products/".$ID."_thumb.jpg"); }
    */
    $deleteinfo = "IP: ".$_SERVER['REMOTE_ADDR'].", Page: admin_product_f.php, Date:".date("m/d/y");
    //mysql_query("UPDATE products SET status='deleted', loginfo='$deleteInfo' WHERE ID='$ID'");
    mysql_query("DELETE FROM products WHERE ID='$ID'");
    
    if ($_GET['pg']=="ws") { 
      header("location: admin_wsproducts.php?cat=$category&chk=3");
    } else { 
      header("location: admin_products.php?cat=$category&chk=3");    
    }
    
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

    header("location: admin_product.php?ID=$ID&chk=5");
    break;
  

  case "product_dimg":

    mysql_query("update products set image='' where ID='$ID'");
    if (file_exists("products/".$ID.".jpg")) { unlink("products/".$ID.".jpg"); }
    if (file_exists("products/".$ID."_thumb.jpg")) { unlink("products/".$ID."_thumb.jpg"); }
    header("location: admin_product.php?ID=$ID&chk=4");
    exit;
    break;
    
    

}
?>