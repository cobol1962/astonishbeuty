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
?>
<!DOCTYPE html>
<head>
 <link rel="stylesheet" href="/css/style.css">
  
    <link rel="stylesheet" href="/css/menu.css">
    <link rel="stylesheet" href="/css/buttons.css">
    <link rel="stylesheet" href="/css/responsive.css">
    <link rel="stylesheet" href="/css/flexslider.css" type="text/css" media="screen" /> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" type="text/css" media="screen" /> 
    
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
	 <link href="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/css/bootstrap.min.css"
        rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="/js/header.js"></script>
	
</head>
 <style type="text/css">
	iframe {
		width:100%;
		min-width:100%;
		max-width:100%;
		height:auto;
	}
	#content {
		width:100%;
		min-width:100%;
		max-width:100%;
		height:auto;
	}
	div {
		display:inline-block;
		height:auto;
	}
	textarea {
		width:100%;
		height:100%;
	}
 </style>
 <body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Asonishing beauty CMS panel</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#">Dashboard</a></li>
      <li><a href="javascript:window.frames['hiddenContent'].previewPage();">Preview</a></li>
      <li><a href="javascript:window.frames['hiddenContent'].editPage();">Edit</a></li> 
      <li><a href="javascript:window.frames['hiddenContent'].saveContent();">Save</a></li> 
    </ul>
  </div>
</nav>

<iframe id="hiddenContent" name="hiddenContent" src=""></iframe>

<script type="text/javascript">
var doc = null;
var js_row = null;
var pageid = null;
 $(document).ready(function() {
	js_row = <?php echo json_encode($page_row );?>;
	pageid = "<?=$ID?>";
	$("#hiddenContent").load( function() {
      document.getElementById("hiddenContent").style.height = $("#hiddenContent").contents().height() + "px";
	  doc =  $("#hiddenContent").contents();
	  enableEditors();
	});
	document.getElementById("hiddenContent").src = "/<?=$page_row['url']?>";
 });
function enableEditors() {
	$.each($(doc).find("[cms]"),function() {
		var ths = this;
		$(this).html("<textarea class='ckeditor' style='width:100%;height:100;' id='" + $(this).attr("cms") + "'>" + js_row[$(this).attr("cms")] + "</textarea>");
		
	});
	window.frames["hiddenContent"].setEditors();
}


</script>

</body>
</html>
