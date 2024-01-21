<?php

namespace api\models;

use common\components\exceptions\SystemException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends \common\models\User
{
    private static function getSecretKey(): string
    {
        return $_ENV['JWT_SECRET_KEY'];
    }

    public function generateJwtToken(): string
    {
        $payload = [
            'iss' => Yii::$app->params['jwtIssuer'],
            'sub' => $this->id,
            'iat' => time(),
            'exp' => time() + Yii::$app->params['jwtExpire'],
        ];

        return JWT::encode($payload, self::getSecretKey(), 'HS256');
    }

    public function generateRefreshToken(): string
    {
        try {
            return RefreshTokens::createToken($this->id, Yii::$app->params['jwtRefreshExpire']);
        } catch (\Exception|SystemException $exception) {
            return 'Не удалось создать refresh токен';
        }
    }

    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        try {
            $decoded = JWT::decode($token, new Key(self::getSecretKey(), 'HS256'));
            return static::findOne(['id' => $decoded->sub]);
        } catch (\Exception $e) {
            return null;
        }
    }

}
