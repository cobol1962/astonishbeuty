<? include("header.php"); ?>

  <h1>Login Required</h1>
  
  <? if ($_GET['error']=="loginfailed") { ?><div class="error">The email or password you entered was invalid.</div><? } ?>
  <? if ($_GET['error']=="restricted") { ?><div class="error">The page you were attempting to access requires authentication.</div><? } ?>
  
  <form action="a_funcs.php" method="post" id="login">
  <input type="hidden" name="cmd" value="login">
    
    <table style="color: #fff;" cellspacing="10" cellpadding="10">
    <tr>
      <td><strong>Email:</strong><br /><input type="text" name="email" size="20" placeholder="Enter Email..."></td>
      <td><strong>Password:</strong><br /><input type="password" name="password" size="20" placeholder="Enter Password..."></td>
      <td><br /><input type="submit" value="Login"></td>
    </tr>
    </table>
  
  </form>	


<? include("footer.php");?>