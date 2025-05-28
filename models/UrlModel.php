<?php

namespace app\models;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
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
class UrlModel extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'url';
    }


    /**
     * функция для сохранения времени
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function() { return time(); },
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['url_to', 'required'],
            [['created_at', 'updated_at', 'views'], 'integer'],
            [['created_at', 'updated_at',],'safe'],
            [['url_sort'], 'string', 'max' => 100],
            [['url_to'], 'url', 'defaultScheme' => 'https'],
            ['url_to', 'validateUrl'],
        ];
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->url_sort=$this->getSortUrl();
            }
            return true;
        }
        return false;
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
    public function validateUrl()
    {
        if (empty($this->url_to)) {
            $this->addError('url_to', 'Поле URL не может быть пустым.');
            return; // Прекращаем дальнейшую проверку
        }

        // Дополнительная проверка формата URL
        if (!filter_var($this->url_to, FILTER_VALIDATE_URL)) {
            $this->addError('url_to', 'Некорректный формат URL.');
            return;
        }

        // Проверка доступности сайта
        $headers = @get_headers($this->url_to);
        if (!$headers || !preg_match('/^HTTP\/\d\.\d\s+(200|301|302)/', $headers[0])) {
            $this->addError('url_to', 'URL не отвечает или возвращает ошибку.');
        }
    }

        /**
         * @return string
         * функция для сокращения url
         */
        public function getSortUrl()
        {
            return $this->url_to;
        }

        public function getLogUrl()
        {
            return $this->hasMany(UrlLogModel::class, ['url_id' => 'id']);
        }
}
