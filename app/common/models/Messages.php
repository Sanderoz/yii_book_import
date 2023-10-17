<?php

namespace common\models;

/**
 * This is the model class for table "{{%messages}}".
 *
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
class Messages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%messages}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user', 'created_at'], 'integer'],
            [['created_at', 'phone', 'email', 'name', 'message'], 'required'],
            [['message'], 'string'],
            [['phone'], 'string', 'max' => 20],
            [['email', 'name'], 'string', 'max' => 255],
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
     * Gets query for [[User0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserModel()
    {
        return $this->hasOne(User::class, ['id' => 'user']);
    }
}
