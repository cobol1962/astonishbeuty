<? 
include("admin_auth.php");
include("global.inc.php");
//include("editor/fckeditor.php");
$ID = $_GET['ID'];
$c_sql = "SELECT * FROM pages where ID='".$ID."'";
$c_result = mysql_query($c_sql, $conn);
$c_row = mysql_fetch_array($c_result);
$body = html_entity_decode($c_row['body']);
$name = $c_row['name'];
include("header.php");
include("func.inc.php");
$_SESSION['editor_upload_dir']=$editor_upload_dir;
?>
<script src="ckeditor/ckeditor.js"></script>

<br /><br />
      
      <? if ($_GET['chk']) { ?><div class="success">** This Page has been updated.</div><br /><br /><? } ?>
      
      <form action="admin_pagef.php" method="post" enctype="multipart/form-data">  
      <input type="hidden" name="ID" value="<?=$ID?>">
      <table cellspacing="10" cellpadding="10">
      <tr><td colspan="2">Page Title: <input type="text" name="name" size="50" value="<?=$name?>">
            <div style="float: right; width: 130px;"><input type="submit" value="SAVE CHANGES" class="greenButton"></div>
      </td></tr>
      <tr><td colspan="2">
        <textarea rows="4" cols="75" id="body" name="body"><?=$c_row['body']?></textarea>
        <?=ckEditor("body","980","500","Full"); ?>      
      </td></tr>
      </table>
      
      <div style="padding-left: 30px;"><input type="submit" value="SAVE CHANGES"  class="greenButton"></div>
      
      </form>


<br /><Br />


<?
  include("footer.php");
?>