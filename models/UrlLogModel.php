<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "url_log".
 *
 * @property int $id
 * @property int $created_at
 * @property int $id_url
 * @property string $url_from
 * @property string $ip
 *
 * @property Url $url
 */
class UrlLogModel extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'url_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'id_url', 'url_from', 'ip'], 'required'],
            [['created_at', 'id_url'], 'integer'],
            [['url_from'], 'string', 'max' => 250],
            [['ip'], 'string', 'max' => 50],
            [['id_url'], 'exist', 'skipOnError' => true, 'targetClass' => Url::class, 'targetAttribute' => ['id_url' => 'id']],
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
            'id_url' => 'Id Url',
            'url_from' => 'Url From',
            'ip' => 'Ip',
        ];
    }

    /**
     * Gets query for [[Url]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUrl()
    {
        return $this->hasOne(Url::class, ['id' => 'id_url']);
    }

}
