<?
include("a_auth.php");
include("../global.inc.php");
include("../func.inc.php");
$category = $_GET['cat'];
$category_name = get_row("product_cats","ID='".$category."'","name");
include("header.php");
$imgdir = "products";
?>


<div class="a_container">
      
      <h1><?=$category_name?> Products &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       <a href="a_product.php" class="lightBlueButton">Add Product</a> 
       <a href="a_categories.php" class="lightBlueButton">Edit Categories</a>
      </h1>
      
      <div style="font-size: 12pt;">
        <? 
        $sep = 1;
        $cats_sql = mysql_query("SELECT * FROM product_cats where parent_ID='0'");
        while ($cats_row = mysql_fetch_array($cats_sql)) {
        if ($sep!="1") { $sep_disp = " | "; } else { $sep_disp = ""; }
        ?>
          <?=$sep_disp?> <a href="a_products.php?cat=<?=$cats_row['ID'];?>"  class="greyButton2" style="font-size: 12pt;"><?=$cats_row['name'];?></a>
        <?
        $sep++;
        }
        ?>
      </div>
      <br /><br /><br />
      
      <? if ($_GET['chk']=="1") { ?><div class="success">* Product has been <strong>Added</strong>.</div><br /><br /><? } ?>
      <? if ($_GET['chk']=="2") { ?><div style="success">* Product has been <strong>Modified</strong>.</div><br /><br /><? } ?>
      <? if ($_GET['chk']=="3") { ?><div style="success">* Product has been <strong>Deleted</strong>.</div><br /><br /><? } ?>
      
      <div id="productContainer">
      
      <?
       if (!$category) { $category = "1"; }
       $query = mysql_query("select * from products where category='$category' and status!='deleted'");
       while($info = mysql_fetch_array($query)) {
      ?>
      
           <div class="shop-products"> 
                    <div class="shop-stock-label"><img src="../images/in-stock.png" /></div>           
                    <div class="home-products-image"><a href="../<?=$imgdir?>/<?=$info['ID']?>.jpg" rel="pgallery"><img src="<?=$imgdir?>/<?=$info['ID']?>.jpg?rand=<?=rand(1,99);?>"></a></div>
                    <div class="home-products-text">
                    <?=$info['name'];?><br />
                    <strong><?=$info['category'];?></strong>
                    </div>
                    <div class="home-products-price">- $<?=$info['price'];?> -</div>                    
                    <a href="a_product.php?ID=<?=$info['ID']?>" class="prodEditButton"><div class="shop-products-buy">EDIT</div></a>
                    <a href="a_funcs.php?ID=<?=$info['ID']?>&category=<?=$category?>&cmd=product_del" onClick="return confirm('Are you sure?')" class="prodEditButton"><div class="shop-products-info">DELETE</div></a>
          </div>
          
      
      <?
       }
      ?>
      </div>
      <div style="clear: left;"></div>

</div>

<? include("footer.php"); ?>