<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Astonishing Beauty</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <link href="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/css/bootstrap.min.css"
        rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/js/bootstrap.min.js"></script>
    <link href="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css"
        rel="stylesheet" type="text/css" />
    <script src="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.js"
        type="text/javascript"></script>
	<link rel="stylesheet" href="../css/sweetalert2.css" type="text/css" media="screen" /> 
    <script src="../js/header.js"></script>
	<script src="../js/sweetalert2.js"></script>
	<script src="../js/base64.js"></script>
    <link rel="stylesheet" href="../css/buttons.css">

    <style>
      @import 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800';
      
      @font-face {
          font-family: 'bebas_neueregular';
          src: url('../fonts/bebasneue-webfont.woff2') format('woff2'),
               url('../fonts/bebasneue-webfont.woff') format('woff');
          font-weight: normal;
          font-style: normal;
      
      }

      body {
         margin:0;
         padding:0;
         background-color:#FFFFFF;
         font-family: 'Open Sans', sans-serif;
         font-size:14px;
         line-height:1.4em;
         color:#666;
         font-weight:400;
      }
      
      a {
          transition: color 0.2s ease-in-out;
          -moz-transition: color 0.2s ease-in-out;
          -webkit-transition: color 0.2s ease-in-out;
          -o-transition: color 0.2s ease-in-out;
      	color: #761370;
          text-decoration: none;
      }
      
      a:hover {
          color: #000;	
          text-decoration: underline;
      }
      
      
      #admin_header { width: 100%; background: #f1f1f1; margin-bottom: 60px; position: fixed; z-index: 999999; }
      #admin_header .logo { width: 200px; float: left; margin-right: 60px; text-align: center; }
      #admin_header .nav { width: 600px; float: left; padding-top: 40px; height: 80px; }
      #admin_header .logo img { width: 200px;  }
      
      #admin_body { padding-top: 170px; }
      
      p {
         font-family: 'Open Sans', sans-serif;
         font-size:14px;
         line-height:1.4em;
      }
      
      h1 { 
          font-family: 'Open Sans', sans-serif;
      	font-size:32px;
      	font-weight:300;
      } 
      
      .topmenulink {
         color:#761370;
         font-size:28px;
         font-family: 'bebas_neueregular';
         padding-right: 15px;
      }
      #admin_header .inner { width: 95%; margin: 0 auto; }
      
      #admin_body { width: 95%; margin: 0 auto; }
      
      .chk { color: green; font-weight: bold; font-size: 12pt; margin-bottom: 20px;  }
      .error { color: red; font-weight: bold; font-size: 12pt; margin-bottom: 20px; }
      
      #customer_add, #admin_add, .customer_edit, .admin_edit { display: none; position: fixed; top: 150px; left: 100px; background: #fff; padding: 15px; border: 1px solid #e9e9e9; box-shadow: 1px 1px 15px #ccc; }
      #customer_filter, #admin_filter, #filter_box { display: none; position: absolute; top: 100px; left: 300px; background: #fff; padding: 15px; border: 1px solid #e9e9e9; box-shadow: 1px 1px 11px #ccc; }
      
      #adminTable td { border-bottom: 1px solid #393783;  padding: 15px; }
      
      .tdHdr td { background: #f1f1f1; padding: 3px; border-bottom: 1px solid #e9e9e9; font-weight: bold; }
      
      .edit_page_button { width: 100px; position: fixed; top: 20px; right: 50px; z-index: 20002; } 



.home-products {
  width:180px;
  float:left;
  display:inline-block;
  background-color:#fff;
}

.home-products-image {
  width:102px;
  margin-left:38px;
  margin-top:15px;
  margin-bottom:10px;
}

.home-products-text {
  width:180px;
  margin-bottom:10px;
  text-align:center;
  color:#761370;
  font-size:13px;
}

.home-products-price {
  width:180px;
  margin-bottom:10px;
  text-align:center;
  color:#666;
  font-size:18px;
  font-weight:700;
}

.home-products-buy {
  float:left;
  display:block;
  width:100%;
  background-color:#761370;
  padding-top:6px;
  padding-bottom:6px;
  color:#fff;
  font-family: 'bebas_neueregular';
  font-size: 18px;
  text-decoration:none;
  text-align:center; 
  margin-top:2px;
  text-decoration:none;
  -webkit-transition: background-color 500ms linear;
  -moz-transition: background-color 500ms linear;
  -o-transition: background-color 500ms linear;
  -ms-transition: background-color 500ms linear;
  transition: background-color 500ms linear;  
}

.home-products-buy:hover {
  float:left;
  display:block;
  width:100%;
  background-color:#000;
  padding-top:6px;
  padding-bottom:6px;
  color:#fff;
  font-family: 'bebas_neueregular';
  font-size: 18px;
  text-decoration:none;
  text-align:center; 
  margin-top:2px;
  text-decoration:none;  
  cursor: pointer;
}

.shop-products {
  width:180px;
  float:left;
  display:inline-block;
  background-color:#fff;
  margin-right:25px;
  margin-bottom:20px;
}

.shop-stock-label {
  position:absolute;
  width: 50px;
  height:42px;
  z-index:20;
  margin-left:132px;
  margin-top:0px;
}


.shop-products-buy {
  float:left;
  display:block;
  width:89px;
  background-color:#761370;
  padding-top:6px;
  padding-bottom:6px;
  color:#fff;
  font-family: 'bebas_neueregular';
  font-size: 18px;
  text-decoration:none;
  text-align:center; 
  margin-top:2px;
  margin-right:1px;
  text-decoration:none;
  -webkit-transition: background-color 500ms linear;
  -moz-transition: background-color 500ms linear;
  -o-transition: background-color 500ms linear;
  -ms-transition: background-color 500ms linear;
  transition: background-color 500ms linear;  
  border-right: solid 1px #fff;
}

.shop-products-buy:hover {
  background-color:#000;
  color:#fff;
}

.shop-products-info {
  float:left;
  display:block;
  width:89px;
  background-color:#761370;
  padding-top:6px;
  padding-bottom:6px;
  color:#fff;
  font-family: 'bebas_neueregular';
  font-size: 18px;
  text-decoration:none;
  text-align:center; 
  margin-top:2px;
  text-decoration:none;
  -webkit-transition: background-color 500ms linear;
  -moz-transition: background-color 500ms linear;
  -o-transition: background-color 500ms linear;
  -ms-transition: background-color 500ms linear;
  transition: background-color 500ms linear;  
}

.shop-products-info:hover {
  background-color:#000;
  color:#fff;
}
      

      
    </style>      
      
    </head>
    
    <body>
    
    <div id="admin_header">
    <div class="inner">
    
     <div class="logo"><img src="../images/logo.png"><br/><strong>ADMIN CONTROL PANEL</strong></div>
     
     <div class="nav">
       <a href="a_dashboard.php" class="topmenulink">Dashboard</a>
       <a href="a_products.php" class="topmenulink">Shop</a>
       <a href="a_page_edit.php?ID=1" class="topmenulink">Home Page</a>
       <a href="a_page_edit.php?ID=2" class="topmenulink">About</a>
       <a href="a_page_edit.php?ID=3" class="topmenulink">Contact</a>
     </div>

    </div>
    <br style="clear: both;" />
    </div>
    
    <div id="admin_body">
    
