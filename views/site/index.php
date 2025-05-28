<?php
use Da\QrCode\QrCode;

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="mx-auto bg-light p-3">
    <?php $form = ActiveForm::begin([
        'action' => '/web/get-url',
        'method' => 'post',
        'id' => 'form-qr',
      //  'enableAjaxValidation' => true,
    ]); ?>
    <div class="input-group">
        <?= $form->field($model, 'url_to')->textInput(['class' => 'form-control w-100', 'placeholder' => 'Введите URL'])->label(false) ?>
        <div class="input-group-append">
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<< JS
  
  
  
  
JS;
$this->registerJs($script, yii\web\View::POS_END);
?>