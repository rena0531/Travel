<footer id="footer">
  copyright Rena All Rights Reserved.
</footer>
<script src="jquery.js"></script>
<script>
$(function(){

// お気に入り登録・削除
var $like,
    likePlanId;
$like = $('.js-click-like') || null; 
likePlanId = $like.data('planid') || null;
if(likePlanId !== undefined && likePlanId !== null){
  $like.on('click',function(){
    var $this = $(this);
    $.ajax({
      type: "POST",
      url: "ajaxLike.php",
      data: { planId : likePlanId}
    }).done(function( data ){
      console.log('Ajax Success');
      $this.toggleClass('active');
    }).fail(function( msg ) {
      console.log('Ajax Error');
    });
  });
}
});
</script>

</body>
</html>