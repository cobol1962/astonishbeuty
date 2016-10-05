<?
include("a_auth.php");
include("../global.inc.php");
include("../func.inc.php");
include("header.php");

$fields = array("name","category","sub_category","price","description","description_full","featured","status","image");

$ID = mysql_escape_string($_GET['ID']);

if ($ID) {
  $query = mysql_query("SELECT * FROM products where ID='".$ID."'");
  $info = mysql_fetch_array($query);
  foreach($fields as $currField) { $$currField = $info[$currField]; }
  $cmd = "product_edit";
} else { 
  foreach($fields as $currField) { $$currField = $_SESSION["asf_".$currField]; }
  if (!$category) { $category = $_GET['cat']; }
  $cmd = "product_add";
}
?>

<div class="admin_container">
          
          
          <? if ($cmd=="product_add") { ?>
          <h1>Adding Product</h1>
          Fill in the fields below to add a new product to the store, then click "Save Product" when done.<br /><br />
          <? } else { ?>
          <h1>Editing Product</h1>
          Make your changes in the form below to modify this product, then click "Save Product" when done.<br /><br />
          <? } ?>
          
          <? if ($_GET['error']=="1") { ?><div style="color: red;">There were missing fields, please fill in all required fields (marked by asterik).</div><br /><br /><? } ?>
          
          <form action="a_funcs.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="cmd" value="<?=$cmd?>">
          <input type="hidden" name="ID" value="<?=$_GET['ID'];?>">
          <table cellspacing="10" cellpadding="5">
          <tr><td>Product Name:</td><td><input type="text" name="name" size="55" value="<?=$name?>"></td></tr>
          <tr><td>Category:</td><td>    
                <select name="category">
                  <option value=""></option>
                  <? 
                  $cats_sql = mysql_query("SELECT * FROM product_cats where parent_ID='0'");
                  while ($cat = mysql_fetch_array($cats_sql)) {
                    if ($cat['ID']==$category) { $sel = "selected"; } else { $sel = ""; }
                  ?>
                    <option value="<?=$cat['ID'];?>" <?=$sel?>><?=$cat['name'];?></option>
                  <? } ?>
                </select>
          </td></tr>
          <?/*
          <tr><td>Sub Category:</td><td>    
                <select name="sub_category">
                  <option value=""></option>
                  <? 
                  $cats_sql = mysql_query("SELECT * FROM product_cats where parent_ID!='0'");
                  while ($cat = mysql_fetch_array($cats_sql)) {
                    if ($cat['ID']==$sub_category) { $sel = "selected"; } else { $sel = ""; }
                  ?>
                    <option value="<?=$cat['ID'];?>" <?=$sel?>><?=$cat['name'];?></option>
                  <? } ?>
                </select>
          
          </td></tr>
          */?>
          <tr><td>Price:</td><td><input type="text" name="price" size="10" value="<?=$price?>"></td></tr>
          <tr><td>Options:<br /><span class="small">(separated by commas)</span></td><td><textarea rows="2" cols="40" name="options"><?=$options?></textarea><br /><span style="font-size: 8pt;">(IE: 6inch=5.00, 12inch=10.00)</span></td></tr>
          <tr><td>Brief Description:</td><td><textarea rows="2" cols="50" name="description"><?=$description;?></textarea></td></tr>
          <tr><td>Full Description:</td><td><textarea rows="10" cols="50" name="description_full"><?=$description_full;?></textarea></td></tr>

          <? if (file_exists("products/".$ID."_thumb.jpg")) { ?>
            <tr><td>* Image:</td><td><img src="products/<?=$_GET['ID']?>_thumb.jpg?rand=<?=rand(1,99);?>" style="max-width: 190px; max-height: 120px;"><br /><a href="a_funcs.php?cmd=product_del_img&ID=<?=$_GET['ID']?>">&raquo; Upload Another Photo</a></td></tr>
          <? } else { ?>
            <tr><td>* Image:</td><td><input type="file" name="image" size="30"></td></tr>
          <? } ?>
          
          
          <? if ($ID) { ?>
          <tr><td>
            <strong>Additional Images:</strong><br />
            <?php
            	$pics_sql = mysql_query("SELECT * FROM product_pics WHERE product_ID=".$ID."");
                 $pics_num = mysql_num_rows($pics_sql);
          	   if($pics_num>0){ $p=0;
          	    while ($pics_res = mysql_fetch_array($pics_sql)) {
            ?>
            <input type="file" size="30" name="images<?php echo $p;?>"><img src="products/<?php echo $pics_res['pic'];?>" style="max-width: 50px; max-height: 50px;"><br />
            <?php $p++; }?>
            <?php for($i=$pics_num;$i<4;$i++){?>
            <input type="file" size="30" name="images<?php echo $i;?>">
            <br />
            <?php }?>
            <?php }else{?>
            <?php for($i=0;$i<4;$i++){?>
            <input type="file" size="30" name="images<?php echo $i;?>">
            <br />
            <?php }?>
             <?php }?>
          </td></tr>
          <? } ?>
          
          <tr><td><br /><br /><input type="submit" value="SAVE PRODUCT" name="submit" class="purpleButton"></td></tr>
          </table>
          </form>

    <br /><br /><br />



</div>


<? include("footer.php"); ?>