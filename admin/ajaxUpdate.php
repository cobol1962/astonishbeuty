<? 
include_once("../global.inc.php");
include_once("../func.inc.php");

$sql = "UPDATE content SET " . $_POST["field"] . "='" . base64_decode($_POST["html"]) . "' WHERE ID='".$_POST["id"]."'";
$update_sql = mysql_query("UPDATE content SET " . $_POST["field"] . "='" . base64_decode($_POST["html"]) . "' WHERE ID='".$_POST["id"]."'");
echo $sql;
?>