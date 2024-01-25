<?php

namespace api\controllers;

use api\models\RefreshTokens;
use api\models\responses\AuthErrorResponse;
use api\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\UnauthorizedHttpException;
use OpenApi\Attributes as OA;

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

    #[OA\Post(
        path: '/auth/get-token',
        summary: 'Получение jwt токена',
        tags: ['auth'],
        parameters: [
            new OA\Parameter(
                name: 'username',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string'),
                example: 'user'
            ),
            new OA\Parameter(
                name: 'password',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string'),
                example: 'user'
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'jwt', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJib29rX3N0b3JlIiwic3ViIjoxLCJpYXQiOjE3MDYyMDIwMDYsImV4cCI6MTcwNjIwOTIwNn0.urE_F0EseDyAKIszfGuYtj5614WhBt81GsRQbX0toeg'),
                        new OA\Property(property: 'refresh', type: 'string', example: 'ZK568TYV3zUjdp7HBLEXJ7l35RATeT5w')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Ошибка',
                content: new OA\JsonContent(ref: AuthErrorResponse::class),
            )
        ]
    )]
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

    #[OA\Post(
        path: '/auth/get-token-by-refresh',
        summary: 'Получение jwt токена по refresh-токену',
        tags: ['auth'],
        parameters: [
            new OA\Parameter(
                name: 'X-Refresh-Token',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string'),
                example: '6eFX2h470Khge0KIEHE2fPaW_LbrXGpA'
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'jwt', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJib29rX3N0b3JlIiwic3ViIjoxLCJpYXQiOjE3MDYyMDIwMDYsImV4cCI6MTcwNjIwOTIwNn0.urE_F0EseDyAKIszfGuYtj5614WhBt81GsRQbX0toeg'),
                        new OA\Property(property: 'refresh', type: 'string', example: 'ZK568TYV3zUjdp7HBLEXJ7l35RATeT5w')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Ошибка',
                content: new OA\JsonContent(ref: AuthErrorResponse::class),
            )
        ]
    )]
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
