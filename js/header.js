$(document).ready(function(){

  $(".toggle").click(function() {
      var link = "#" + $(this).attr('togglediv');
      var showIt = $(link);
      showIt.toggle();
      return false;
  });
  
  
  $( "body" ).scrollTop( 0 );


  
});
