<?
session_start();
include("global.inc.php");
include("func.inc.php");
include("header.php");
include("top-nav.php");
$nosticky = 1; 

$ID = mysql_escape_string($_GET['ID']);
$imgdir = "products";

$fields = array("name","category","price","options","description","image");
$query = mysql_query("SELECT * FROM products where ID='".$ID."'");
$info = mysql_fetch_array($query);
foreach($fields as $currField) { $$currField = $info[$currField]; }
$ppics_ary = array();
$product_pics_sql = mysql_query("SELECT * FROM product_pics WHERE product_ID='".$ID."' ORDER BY sort_order ASC");
while ($ppic = mysql_fetch_array($product_pics_sql)) {
  $ppics_ary[] = $ppic['pic'];
}
//array_push($ppics_ary, $ppics_ary[0]);
//unset($ppics_ary[0]);
?>

<style>
  .productImages * { padding: 0; margin: 0; }
  .main-img {
    width: 420px; max-height: 280px;       border: 10px solid #80583d;
    background: #fff;
    box-shadow: 1px 1px 7px rgba(0,0,0,0.1);
  }
  .thumbnails img {
    max-width: 100%;
    padding: 5px;
    border: 5px solid #80583d;
    height: auto;
    background: #fff;
    box-shadow: 1px 1px 7px rgba(0,0,0,0.1);
  }
  .thumbnails ul {
    list-style: none;
    margin-bottom: 1.5em;
  }
  .main-left {
    width: 380px; 
    margin-bottom: 0.75em;
    float: left;
    margin-right: 30px;
    margin-left: 50px;
  }
  .main-right { 
    width: 380px; 
    color: #fff;
    float: left;    
    font-size: 14pt;
  }
  .thumbnails li {
    display: inline;
    margin: 0 10px 0 0;
    width: 90px;
  }
  .thumbnails img { width: 50px; }
  .cssmenu-mobile
  {
      display:none;
  }
  .addtl_info { color: #fff; margin-left: 50px; font-size: 16pt; }
  
</style>

<script src="js/simplegal.js"></script>
  <script>
  $(document).ready(function () {
    $('.thumbnails').simpleGal({
      mainImage: '.custom'
    });
  });
  </script>


<div class="main-holder">  
  
    
  <div style="text-align: center;">
    <div class="title-holder"><h1><?=$info['name'];?></h1></div>
  </div><br style="clear: both;" /><br />

  <div class="cat_container">
  
        <div class="cat-right">

       <div class="main-left">
              <img src="products/<?=$info['ID'];?>.jpg" class="custom main-img" alt="Product">
              <br />
  	  
  	    <? if (count($ppics_ary)>1) { ?>
              <ul class="thumbnails" style="margin-top: 5px;">
              <? 
              rsort($ppics_ary);
              foreach ($ppics_ary as $currPic) { 
              ?>
              <li><a href="#"><img src="products/<?=$currPic;?>" alt="Product"></a></li>          
              <? } ?>
              </ul>
            <? } ?>
            
            </div>
                              
            <div class="main-right">
          
              <div style="margin-bottom: 10px;"><?=$info['description'];?></div>
                    


                       <form action="cart_add.php" method="post">
                        <input type="hidden" name="ID" value="<?=$info['ID'];?>">
      
                        <? if ($info['options']) { ?>
                            <br />
                            <table  class="options_table" cellspacing="5" cellpadding="5" style="width: 100%;">
                            <tr><td style="width: 50px;">
                              <input type="text" name="qty" value="1" size="2" class="form-qty">
                            </td><td>
                                <?  
                                if ($info['options']) { 
                                  $sizes = explode(",",$info['options']);   
                                }
                                $use_size_as_qty = $info['use_size_as_qty'];
                                if (!$use_size_as_qty) { 
                                  $size_label = "Pack Size:"; 
                                  $first_opt = ""; 
                                } else { 
                                  $size_label = "Qty:"; 
                                  $first_opt = "1";
                                }
                                if (is_array($sizes)) { ?>
                                  <?//=$size_label?>    
                                  <select name="size" class="form-text02" style="font-size: 12pt;">
                                    <? if ($first_opt) { ?><option value="<?=$first_opt?>"><?=$first_opt?></option><? } ?>
                                    <? 
                                      $i = 0; foreach($sizes as $currSize) {
                                      $currSizeAry = explode("=", $currSize);
                                      $currSize = $currSizeAry[0];
                                      $currSizePrice = $currSizeAry[1]; 
                                    ?>
                                    <option value="<?=$currSize?>|<?=$currSizePrice;?>" ><?=$currSize?> - $<?=$currSizePrice?></option>
                                    <? $i++; } ?>
                                  </select>
                                <? } ?>
                            </td>
                            </tr>
                            <tr>
                              <td colspan="2"><input type="image" src="images/add2cart.png" name="add" value="Add to Cart" style="cursor: pointer;"></td>
                            </tr>
                            </table>
                        <? } else { ?>
                            <div class="price">$<?=number_format($info['price'],2);?></div> <input type="image" src="images/add2cart.png" name="add" value="Add to Cart" style="cursor: pointer;">   
                        <? } ?>
                        
                        </form>                 





















              
              
            </div>
              <br  style="clear: both;" /><br />
              
              <div class="addtl_info">
              
                  <? if ($info['ingredients']) { ?>
                  <div class="sectionHdr">Ingredients</div>
                  <div class="sectionInner"><?=$info['ingredients'];?></div>
                  <? } ?>
                  
                  <? if ($info['guaranteed_analysis']) { ?>
                  <div class="sectionHdr">Guaranteed Analysis</div>
                  <div class="sectionInner"><?=$info['guaranteed_analysis'];?></div>
                  <? } ?>
                  
                  <? if ($info['health_allery_notes']) { ?>
                  <div class="sectionHdr">Health / Allergy Notes</div>
                  <div class="sectionInner"><?=$info['health_allery_notes'];?></div>
                  <? } ?>
    
                  <div class="sectionHdr">Full Product Description</div>
                  <div class="sectionInner"><?=$info['description_full'];?></div>
    
                  <div class="sectionHdr">Legal / Safety / Disclaimer</div>
                 
                  <div class="sectionInner">
                      <? 
                      if ($info['legal_notes']) { 
                        echo $info['legal_notes']; 
                      } else { 
                      ?>
                        Processed & Packaged in USDA Approved Facility<br /><br />
                      <? } ?>
                      
                      <strong>Important Notice:</strong> Please supervise your dog when giving any chew or treat. Remove small or broken pieces to prevent a choking hazard. Be mindful of your pet's age and health conditions when selecting products. Please contact a customer service representative for additional product details and recommendations. Not for human consumption. We suggest washing your hands after handling natural treats and chews.
                  </div>
              
              </div>

        </div> 
        
        <div class="cat-left">
            
            <? include("sidebar-categories.inc.php"); ?>
        
        </div>
  
  </div>
 
 
</div>


<div style="clear: left;"></div>

<? include("footer.php"); ?>