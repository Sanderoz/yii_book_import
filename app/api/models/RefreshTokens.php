<?php

namespace api\models;

use common\components\exceptions\SystemException;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * @property string $token
 * @property int $user_id
 * @property bool $used
 * @property int $expired_at
 * @property int $created_at
 *
 * @property User $user
 */
class RefreshTokens extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%refresh_tokens}}';
    }

    public function rules(): array
    {
        return [
            [['token'], 'required'],
            [['user_id', 'expired_at', 'created_at'], 'integer'],
            ['used', 'boolean'],
            ['used', 'default', 'value' => false],
            ['token', 'unique'],
            ['user_id', 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function behaviors(): array
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
     * @throws SystemException
     * @throws Exception
     */
    public static function createToken(int $userId, int $expire): string
    {
        $counter = 0;
        while (true) {
            $token = Yii::$app->security->generateRandomString(32);
            if (self::find()->where(['token' => $token])->exists())
                continue;

            $model = new self([
                'token' => $token,
                'user_id' => $userId,
                'expired_at' => time() + $expire,
                'used' => false
            ]);
            if (!$model->save())
                if ($counter++ < 100)
                    continue;
                else
                    throw new SystemException('Не удалось создать refresh токен');

            break;
        }

        return $token;
    }

    public function setUsed(): void
    {
        $this->used = true;
    }

    public static function findUserByRefresh(string $token): ?User
    {
        if (empty($model = self::find()
            ->where(['token' => $token])
            ->andWhere(['used' => false])
            ->andWhere(['>', 'expired_at', time()])
            ->one()
        ))
            return null;

        /** @var self $model */
        $model->setUsed();
        $model->save();

        return $model->user;
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
