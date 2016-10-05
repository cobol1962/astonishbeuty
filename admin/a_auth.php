<? 
session_start();
if($_SESSION['admin_auth']!="ok") { header("location: a_login.php?error=2"); }
?>