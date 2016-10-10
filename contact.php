<? include("header.php");?>

<br>
<br>
<br>

     



<div style="clear:both;"></div>


<div class="separator"></div>





<!-- CONTENT / BLUE BACKGROUND -->
<div class="blue-section">
    
       <div class="content" style="margin-top:30px;">
           <div class="left-section">
            
            <h3>CONTACT US</h3>
            
            <br />
            
            
            <div style="width: 400px; float: left; margin-right: 40px;">
              <em>Fill out the form below to send an e-mail to us:</em>
              <form action="" method="get">
              <input name="name" type="text" class="form-text" placeholder="Name" /> 
              <input name="email" type="text" class="form-text" placeholder="Email" />
              <textarea name="message" cols="" rows="5" class="form-text" placeholder="Message"></textarea>
              <input name="submit" type="image" value="Send Message" class="purpleButton" />
              </form>
            </div>

            <div style="width: 300px; float: left; margin-right: 20px; font-size: 15pt; line-height: 22pt;">

             <?php for ($i=1;$i<7;$i++) { 
				if (strpos($page_row["content"], "body" . $i) !== false) { ?>
					<div class="cmscontent"><?=$page_row["body" . $i]?></div><br />
				<?php } ?>
			<?php } ?>
             
            </div>

<br />

           </div>
             
           
           <? include("right.php");?>
           
           
       </div>
</div>


<!-- CONTENT / WHITE BACKGROUND -->


    <div class="content">
    
        <div class="title-holder"><br />
        <h2><span style="background-color:#fff;">WHY ASTONISHING BEAUTY?</span></h2>
        </div> 
        
       <div class="separator01"></div>
       
       
        
        <div class="l-content">
          <div class="icon-holder"><img src="images/icon-01.png" /></div>
          
          <div class="icon-text">
            <span class="icon-text-title">HIGHEST QUALITY HAIR EXTENSIONS</span>
            Clip-in Hair Extensions are double drawn and produced with 100% Remy human hair. Because of this, our Hair Extensions are of superior quality, blend naturally with your own hair and can be washed, blow dried, flat ironed, and/or curled using styling tools, just like your own hair!
          </div> 
                                          
        </div> 
        
        
        <div class="r-content">
          <div class="icon-holder01"><img src="images/icon-02.png" /></div>
          
          <div class="icon-text">
            <span class="icon-text-title">CUSTOMER SERVICE</span>
            Feel free to email our customer care team at:<br />
<a href="mailto:info@AstonishingBeaty.com">info@AstonishingBeaty.com</a> or call 843-815-4433 with any questions or concerns you may have.
We pride ourselves in replying to all emails within 24 hours of receipt.
          </div>                            
        </div>   
       
            
    </div>
 
 
<? include("footer.php");?>




