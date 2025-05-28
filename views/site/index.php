<?php
use Da\QrCode\QrCode;

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="mx-auto bg-light p-3">
    <?php $form = ActiveForm::begin([
        'action' => '/site/get-url',
        'method' => 'get',
        'id' => 'form-qr',
      //  'enableAjaxValidation' => true,
    ]); ?>
    <div class="input-group">
        <?= $form->field($model, 'url_to')->textInput([
            'class' => 'form-control',
            'placeholder' => 'Введите URL'
        ])->label(false) ?>
        <div class="input-group-append">
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

