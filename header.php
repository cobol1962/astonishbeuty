<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Astonishing Beauty</title>


    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/buttons.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" /> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" type="text/css" media="screen" /> 
    
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="js/header.js"></script>
      
      
	<script>
        $(document).ready(function(){
            $("#nav-mobile").html($("#nav-main").html());
            $("#nav-trigger span").click(function(){
                if ($("nav#nav-mobile ul").hasClass("expanded")) {
                    $("nav#nav-mobile ul.expanded").removeClass("expanded").slideUp(250);
                    $(this).removeClass("open");
                } else {
                    $("nav#nav-mobile ul").addClass("expanded").slideDown(250);
                    $(this).addClass("open");
                }
            });
        });
    </script>      
      
      

    </head>
    
    
    <body class="loading">
    
    
    <!-- TOP -->
    <div id="top">
    <div class="content">
    <div class="l-content top-text">135 Towne Drive, Bluffton SC | 843-815-4433</div>
    <div class="r-content top-text01">Dont have an account? <a href="#">Register Now</a> | <a href="#">Login</a></div>
    </div>
    </div>
    
    
    <!-- LOGO -->
    <div class="content" style="background-color:#FFFFFF;">
    <div class="logo"></div>
    </div>
    
    
    <!-- MENU -->
    <div class="menu-holder">
    <div id="centeredmenu">
       <ul>
          <li <?php if ($page == 'index.php') { ?>class="active"<?php } ?>><a href="index.php">Home</a></li>
          <li <?php if ($page == 'shop.php') { ?>class="active"<?php } ?>><a href="shop.php">Shop</a></li>
          <li <?php if ($page == 'about-us.php') { ?>class="active"<?php } ?>><a href="about-us.php">About Us</a></li>
          <li <?php if ($page == 'contact.php') { ?>class="active"<?php } ?>><a href="contact.php">Contact Us</a></li>
       </ul>
    </div>
    </div>
          
              <!-- MOBILE MENU --> 
              <div class="topmenu-holder">
                  <ul class="topnav">
                  <li><a href="index.php">HOME</a></li>
                  <li><a href="shop.php">SHOP</a></li>
                  <li><a href="about-us.php">ABOUT US</a></li>
                  <li><a href="contact.php">CONTACT US</a></li>
                  <li class="icon">
                    <a href="javascript:void(0);" style="font-size:16px;" onClick="myFunction()">☰</a>
                  </li>
                </ul>
              </div>
              
                <script>
                function myFunction() {
                    document.getElementsByClassName("topnav")[0].classList.toggle("responsive");
                }
                </script>   
              
              <!-- MOBILE MENU END --> 
    

         

