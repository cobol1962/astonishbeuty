<?
include("a_auth.php");
include("../global.inc.php");
include("../func.inc.php");
 $category = $_GET['cat'];
include("header.php");
?>
<script src="ckeditor/ckeditor.js"></script>

<h1>Categories &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<a href="a_products.php" style="font-size: 10pt;" class="lightBlueButton">Back to Products</a>
</h1>

<? if ($_GET['chk']=="1") { ?><div class="success">* Categories have been <strong>Added</strong>.</div><br /><br /><? } ?>
<? if ($_GET['chk']=="2") { ?><div class="success">* Categories have been <strong>Updated</strong>.</div><br /><br /><? } ?>
<? if ($_GET['chk']=="3") { ?><div style="success">* Category has been <strong>Deleted</strong>.</div><br /><br /><? } ?>

<div  id="categoryAdd">

<strong>Add a New Category:</strong><br />

  <form action="a_funcs.php" method="post">
  <input type="hidden" name="cmd" value="category_add">
  Category Title: <input type="text" name="name" value="" size="30">
  <input type="submit" value="Add" name="submit">
  </form>

</div>


<div id="productContainer">

<br /><br />

Update categories below and click "SAVE CHANGES" button below.<br />

<form action="a_funcs.php" method="post">
<input type="hidden" name="cmd" value="category_edit">

  <table cellspacing="5" cellpadding="5">
    <?
     $query = mysql_query("select * from product_cats");
     while($info = mysql_fetch_array($query)) {
    ?>
    <tr class="btmBorder">
    <td><input type="text" name="name_<?=$info['ID'];?>" value="<?=$info['name'];?>" size="40"></td>
    <td style="width: 220px;">
     <a href="a_funcs.php?ID=<?=$info['ID']?>&cmd=category_del" onClick="return confirm('Are you sure?')"  class="greyButton">Delete</a></td>
    </tr>
    <?
     }
    ?>
  </table><Br /><br />
  
  <input type="submit" value="Save Changes"  class="purpleButton">
  
</form>

</div>
<div style="clear: left;"></div>

<? include("footer.php"); ?>