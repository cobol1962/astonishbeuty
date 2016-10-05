<? 
include_once("../global.inc.php");
include_once("../func.inc.php");
$notabs = 1;
$tab_title = "Content";

$ID = mysql_escape_string($_REQUEST['ID']);
if ($ID) {
  $page_sql = mysql_query("SELECT * FROM content WHERE ID='".$ID."'");
  $page_row = mysql_fetch_array($page_sql);
} else {
  $page_row = array();
  foreach($_SESSION as $key => $value) {
    $page_row[$key] = $value;
  }
}

$edit_page = $ID;
$_SESSION['editor_upload_dir']="/cms_content/";
include("header.php"); 
?>
<script src="../ckeditor/ckeditor.js"></script>
<style>
.tdHdr td { background: #f1f1f1 !important; }
td { text-align: left !important; }
</style>
			
												
<!-------------------- Edit Page --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
<div id="admin_dashboard">

  <?/*<h1><?=$page_row['title'];?></h1>*/?>

  <form action="a_funcs.php" method="post" enctype="multipart/form-data">
  <div style="width: 70px; position: fixed; top: 13px; right: 170px; z-index: 23000;"><input type="submit" value="SAVE PAGE" name="submit" class="purpleButton"></div>
  <input type="hidden" name="cmd" value="page_edit">
  <input type="hidden" name="ID" value="<?=$ID?>">
  <table style="width: 70%;">
  <tr>
    <td><input type="text" name="title" style="font-size: 16pt; width: 970px;" value="<?=$page_row['title'];?>" required></td>
  </tr>
  <tr>
    <td>
       <textarea rows="4" cols="75" id="body" name="body"><?=$page_row['body']?></textarea>
       <?=ckEditor("body","980","550","Full"); ?> 
    </td>
  </tr>
  <? if ($page_row['body2']) { ?>
  <tr>
    <td>Body 2<br />
       <textarea rows="4" cols="75" id="body2" name="body2"><?=$page_row['body2']?></textarea>
       <?=ckEditor("body2","980","550","Full"); ?> 
    </td>
  </tr>
  <? } ?>
  </table>
  
    
  </form>
  
  <br /><br />
 
  
</div>

<? include("footer.php"); ?>