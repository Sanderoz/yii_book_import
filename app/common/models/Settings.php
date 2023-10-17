<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\caching\DbDependency;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%settings}}".
 *
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $key
 * @property string|null $value
 * @property string|null $title
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%settings}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['key', 'value', 'title'], 'string', 'max' => 255],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => time(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'key' => 'Key',
            'value' => 'Value',
            'title' => 'Title',
        ];
    }

    /**
     * @return int|mixed
     */
    public static function getPageCount()
    {
        $result = \Yii::$app->cache->get('page_count');
        if ($result === false) {
            $result = Settings::findOne(['key' => 'page_count'])->value ?? 20;
            \Yii::$app->cache->set('page_count', $result, 3600);
        }
        return $result;
    }
}
