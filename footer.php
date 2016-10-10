

<div class="bottom-grey">
  <div class="content"><img src="images/bottom-img.png" /></div>
</div>   


<div style="clear:both;"></div>



<!-- FOOTER -->

<div class="content">
    <div class="l-content">
    <nav>
      <a href="#">Home</a>
      <a href="#">Shop</a>
      <a href="#">About Us</a>
      <a href="#">Visit Store</a>
      <a href="#">Contact Us</a>
     </nav>
    </div> 
    
    <div class="r-content">
     <div class="social-holder">   
     <div class="instagram"><a href="#" target="_blank"></a></div>
     <div class="twitter"><a href="#" target="_blank"></a></div>
     <div class="facebook"><a href="https://www.facebook.com/astonishingbundles" target="_blank"></a></div>
     <span class="social-text">Follow Us:</span>
     </div>
    </div>
     
</div>
    

<div class="separator01"></div>






 <!-- jQuery -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.min.js">\x3C/script>')</script>

  <!-- FlexSlider -->
  <script defer src="js/jquery.flexslider.js"></script>

  <script type="text/javascript">
    $(function(){
      SyntaxHighlighter.all();
    });
    $(window).load(function(){
    
    });
  </script>
  
  
  <!-- Slider -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.bxslider.min.js"></script>
    <link href="css/jquery.bxslider.css" rel="stylesheet" />
    
    
      <script>
      $(document).ready(function(){
        $('.bxslider').bxSlider({
          slideWidth: 180,
          minSlides: 1,
          maxSlides: 5,
          slideMargin: 45,
          auto: true,
          pager: false,
          moveSlides: 1,
          responsive: true,
        });
		
		$('.bxslider01').bxSlider({
          slideWidth: 180,
          minSlides: 1,
          maxSlides: 5,
          slideMargin: 45,
          auto: true,
          pager: false,
		  controls: false,
          moveSlides: 1,
          responsive: true,
        });
      });
      </script>   
  
   <? if (!$no_sticky) { ?>  
   <!-- Sticky header -->
   <script>
    $(window).scroll(function() {
        if ($(this).scrollTop() > 1){  
            $('#top').addClass("sticky");
			$('.logo').addClass("sticky");
			$('.menu-holder').addClass("sticky");
        }
        else{
            $('#top').removeClass("sticky");
			$('.logo').removeClass("sticky");	
			$('.menu-holder').removeClass("sticky");	
        }
    });
    </script>  
    <? } ?>


</body>
</html>
