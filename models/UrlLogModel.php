<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

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
     *
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],

                ],
                'value' => function() { return time(); },
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->url_from=Yii::$app->request->referrer;
                $this->ip=Yii::$app->request->userIP;
            }
            return true;
        }
        return false;
    }
    public function rules()
    {
        return [
            [['id_url' ], 'required'],
            [['created_at','ip','url_from'], 'safe'],
            [['created_at', 'id_url'], 'integer'],
            [['url_from'], 'string', 'max' => 250],
            [['ip'], 'string', 'max' => 15],
            [['id_url'], 'exist', 'skipOnError' => true, 'targetClass' => UrlModel::class, 'targetAttribute' => ['id_url' => 'id']],
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
            'id_url' => 'Url',
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
        return $this->hasOne(UrlModel::class, ['id' => 'id_url']);
    }


    public function getLogsIp()
    {
        $IP=[];
        foreach ($this->url as $item){
            $IP[$item->ip]=$item->created_at;
        }

        return $IP;
    }
}
