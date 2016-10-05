<? 
include("admin_auth.php");
include("global.inc.php");


foreach($_POST as $key => $value) {
  $_SESSION["asf_".$key]=$value;
  $$key = $value;
  $dbVar = "db_".$key;
  $$dbVar = mysql_escape_string($value);
}

if (!$ID) { $ID = $_GET['ID']; }
if (!$cmd) { $cmd = $_GET['cmd']; }
if (!$category) { $category = $_GET['category']; }

// Check required fields
unset($_SESSION['missingFields']);
$required_fields = array("name","price");
foreach ($required_fields as $currReq) {
  if (!$_POST[$currReq]) { 
    $missingFields = 1;
    $_SESSION['missingFields'][]=$currReq;
  }
}
if (($cmd=="edit" || $cmd=="add") && !$_FILES['image']['tmp_name'] && !file_exists("products/".$ID.".jpg")) {
  $missingFields = 1;
  $_SESSION['missingFields'][]="image";
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


switch($cmd) {

  case "add":

    if ($missingFields=="1") {
      header("location: admin_product.php?cat=$cat&error=1");
    } else {
      mysql_query("insert into products set name='$db_name', price='$db_price', category='$db_category', options='$db_options', description='$db_description', featured='$featured', date_added=now()") or die(mysql_error());
      $ID = mysql_insert_id();
      uploadImage($ID);
      foreach($_SESSION as $key => $value) { if (substr($key,0,4)=="asf_") { unset($_SESSION[$key]); } }
      header("location: admin_products.php?cat=$category&chk=1");    
    }
    exit;
    break;

  case "editws":
  
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

  case "edit":
  
   if ($missingFields=="1") {
      header("location: admin_product.php?cat=$category&ID=$ID&error=1");
    } else {
      if ($_FILES['image']['tmp_name']) { uploadImage($ID); }
      mysql_query("update products set name='$db_name', price='$db_price', category='$db_category', options='$db_options', description='$db_description', featured='$featured', date_added=now() WHERE ID='$ID'");
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
 
  case "del":
    /*
    mysql_query("DELETE FROM products where ID='$ID'");
    if (file_exists("products/".$ID.".jpg")) { unlink("products/".$ID.".jpg"); }
    if (file_exists("products/".$ID."_thumb.jpg")) { unlink("products/".$ID."_thumb.jpg"); }
    */
    $deleteinfo = "IP: ".$_SERVER['REMOTE_ADDR'].", Page: admin_product_f.php, Date:".date("m/d/y");
    mysql_query("UPDATE products SET status='deleted', loginfo='$deleteInfo' WHERE ID='$ID'");
    
    if ($_GET['pg']=="ws") { 
      header("location: admin_wsproducts.php?cat=$category&chk=3");
    } else { 
      header("location: admin_products.php?cat=$category&chk=3");    
    }
    
    exit;
    break;

  case "addimg":

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
  

  case "dimg":

    mysql_query("update products set image='' where ID='$ID'");
    if (file_exists("products/".$ID.".jpg")) { unlink("products/".$ID.".jpg"); }
    if (file_exists("products/".$ID."_thumb.jpg")) { unlink("products/".$ID."_thumb.jpg"); }
    header("location: admin_product.php?ID=$ID&chk=4");
    exit;
    break;

}
?>