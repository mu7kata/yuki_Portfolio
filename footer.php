<footer id="footer">
</footer>
<script src="js/vendor/jquery-2.2.2.min.js"></script>
<script>
    $(function(){

        // メッセージ表示
        var $jsShowMsg = $('#js-show-msg');
        var msg = $jsShowMsg.text();
        if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
            $jsShowMsg.slideToggle('slow');
            setTimeout(function(){ $jsShowMsg.slideToggle('slow'); }, 5000);
        }

       
    });


</script>
