<?php
use Da\QrCode\QrCode;

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="mx-auto bg-light p-3 rounded">
    <?php $form = ActiveForm::begin([
        'action' => \yii\helpers\Url::to('/web/get-qr'),
       'method' => 'post',
        'id' => 'form-qr',
      // 'enableAjaxValidation' => true,
    ]); ?>
    <div class="input-group">
        <?= $form->field($model, 'url_to',['options'=>['class' => 'flex-grow-1']])->textInput(['class' => 'form-control w-100', 'placeholder' => 'Введите URL'])->label(false) ?>
        <div class="input-group-append">
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="card"  id="block-qr" style="width: 18rem; visibility: hidden;" >
    <img id="qr-code-image" src="" alt="QR Code" class="img-fluid" style="width: 250px; height: 250px;">
    <div class="card-body">
        <p class="text-muted">
            Короткая ссылка : <a href="#" id='url-short' class="text-reset">reset link</a>.
        </p>
    </div>
</div>
<div class="card"  id="info-qr" style="width: 18rem; visibility: hidden;" >
    <div class="card-body">
    </div>
</div>

<?php
$script = <<< JS
  $(document).on('submit',function(e) {
    e.preventDefault();
    
    var form = $('#form-qr');
    var button = $('#submit-btn');
    
    button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Обработка...');
    
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data:form.serialize(),
        success: function(response) {
            if(response.success){
             $('#qr-code-image').attr('src', response['data']['url-image']);
             $('#url-short').attr('href','/web/redirect-qr?url='+response['data']['url']);
             $('#url-short').text(response['data']['short_url']);
             $('#block-qr').css('visibility','visible');
             $('#info-qr').css('visibility','visible');
             $('#block-qr').css('visibility','visible');
            console.log(response);
            }else {
            alert(response.error);
            }

        },
        error: function(xhr) {
              $('#block-qr').empty();
                  $('#block-qr').css('visibility','hidden');
            console.error('Ошибка:', xhr.responseText);
        },
        complete: function() {
            button.prop('disabled', false).text('Найти');
        }
    });
});
  
JS;
$this->registerJs($script, yii\web\View::POS_END);
?>

