<?php
use yii\grid\GridView;
use yii\helpers\Html;
echo Html::tag('H3', 'Журнал:', );
echo '<hr/>';
echo GridView::widget([
    'dataProvider' => $provider,
    'columns'=>[
      'id',
       [
           'attribute'=>'created_at',
           'value'=>function($model){
                return date('d.m.Y H:i:s', $model->created_at);
           }
       ],
        [
            'attribute'=>'id_url',
                'value'=>function($model){
                return $model->url->url_to;
            }
        ],
        [
            'attribute'=>'url_from',

        ],
        'ip',

    ],
]);