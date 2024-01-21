<?php

namespace api\controllers;

use api\models\RefreshTokens;
use api\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\UnauthorizedHttpException;

class AuthController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'getToken' => ['post'],
                    'getTokenByRefresh' => ['post']
                ],
            ],
        ];
    }

    /**
     * @throws UnauthorizedHttpException
     */
    public function actionGetToken(): array
    {
        if (empty(Yii::$app->request->post('username')) || empty(Yii::$app->request->post('password')))
            throw new UnauthorizedHttpException('Отсутсвует логин/пароль');

        if (empty($user = User::findOne(['username' => Yii::$app->request->post('username')])))
            throw new UnauthorizedHttpException('Пользователь не найден');

        if (!$user->validatePassword(Yii::$app->request->post('password')))
            throw new UnauthorizedHttpException('Пароль не верен');

        return [
            'jwt' => $user->generateJwtToken(),
            'refresh' => $user->generateRefreshToken()
        ];
    }

    /**
     * @throws UnauthorizedHttpException
     */
    public function actionGetTokenByRefresh()
    {
        if (empty($refresh = Yii::$app->request->headers->get('X-Refresh-Token')))
            throw new UnauthorizedHttpException('Отсутсвует refresh токен');

        if (empty($user = RefreshTokens::findUserByRefresh($refresh)))
            throw new UnauthorizedHttpException('Токен неверен или истек');

        return [
            'jwt' => $user->generateJwtToken(),
            'refresh' => $user->generateRefreshToken()
        ];
    }

}
