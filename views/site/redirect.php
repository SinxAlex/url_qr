<div id="message-box" style="">
    <div class="alert alert-info">
        <p id="message-text"></p>
        <p>Перенаправление через по <?=$url;?>
            <span id="countdown" class="flex-d">5</span> секунд...</p>
    </div>
</div>
<?php
$script = <<< JS
  $(document).ready(function(e) {
    function startCountdown(seconds, redirectUrl) {
        var countdownElement = $('#countdown');
        countdownElement.text(seconds).show();
        
        var countdownInterval = setInterval(function() {
            seconds--;
            countdownElement.text(seconds);
            
            if (seconds <= 0) {
                clearInterval(countdownInterval);
                window.location.href = redirectUrl;
            }
        }, 1000);
    }
    var redirectUrl = "$url";
     startCountdown(5, redirectUrl);
      
});
  
JS;
$this->registerJs($script, yii\web\View::POS_END);
?>
