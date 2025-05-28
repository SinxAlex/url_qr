<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "url".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $url_from
 * @property string $url_to
 * @property string $url_sort
 * @property string $ip
 * @property int $views
 */
class UrlModel extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'url';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'url_from', 'url_to', 'url_sort', 'ip', 'views'], 'required'],
            [['created_at', 'updated_at', 'views'], 'integer'],
            [['url_from', 'url_to'], 'string'],
            [['url_sort'], 'string', 'max' => 100],
            [['ip'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'url_from' => 'Url From',
            'url_to' => 'Url To',
            'url_sort' => 'Url Sort',
            'ip' => 'Ip',
            'views' => 'Views',
        ];
    }

}
