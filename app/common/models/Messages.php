<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int|null $user
 * @property int $created_at
 * @property string $phone
 * @property string $email
 * @property string $name
 * @property string $message
 *
 * @property User $userModel
 */
class Messages extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%messages}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => time(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user'], 'integer'],
            [['name', 'message'], 'required'],
            [['message'], 'string'],
            [['phone'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 255],
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^\+\d \(\d{3}\) \d{3}-\d{2}-\d{2}$/'],
            ['user', 'default', 'value' => \Yii::$app->getUser()->id ?? null],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'Пользователь',
            'created_at' => 'Дата создания',
            'phone' => 'Телефон',
            'email' => 'Email',
            'name' => 'Имя',
            'message' => 'Сообщение',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserModel()
    {
        return $this->hasOne(User::class, ['id' => 'user']);
    }
}
