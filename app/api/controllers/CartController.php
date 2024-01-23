<?php

namespace api\controllers;

use api\models\responses\AuthErrorResponse;
use api\models\responses\BooksIndexResponse;
use common\components\exceptions\SystemException;
use common\models\Books;
use common\models\CartItems;
use Throwable;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use OpenApi\Attributes as OA;

class CartController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'add' => ['post'],
                    'minus' => ['post'],
                    'clear' => ['post'],
                ],
            ],
        ];
    }

    #[OA\Post(
        path: '/cart/add/{isbn}',
        summary: 'Добавление книги в корзину покупателя',
        security: [
            ["bearerAuth" => []]
        ],
        tags: ['cart'],
        parameters: [
            new OA\Parameter(
                name: 'isbn',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(description: 'Количество в корзине', example: 3)
            ),
            new OA\Response(
                response: 401,
                description: 'Ошибка',
                content: new OA\JsonContent(ref: AuthErrorResponse::class),
            )
        ]
    )]
    /**
     * @throws SystemException
     */
    public function actionAdd(string $isbn): ?int
    {
        if ($book = Books::findOne(['isbn' => $isbn]))
            try {
                return CartItems::addItemInCart($book, Yii::$app->user->id);
            } catch (\Exception $exception) {
                throw new SystemException('Не удалось добавить книгу');
            }
        else
            throw new SystemException('Книга не найдена');
    }

    #[OA\Post(
        path: '/cart/minus/{isbn}',
        summary: 'Уменьшение количества книг в корзинк покупателя на 1/удаление всех',
        security: [
            ["bearerAuth" => []]
        ],
        tags: ['cart'],
        parameters: [
            new OA\Parameter(
                name: 'isbn',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'all',
                description: 'При значении 1 - удаляет переданную книгу из корзины',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 0)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(description: 'Количество данных книг в корзине', example: 2)
            ),
            new OA\Response(
                response: 401,
                description: 'Ошибка',
                content: new OA\JsonContent(ref: AuthErrorResponse::class),
            )
        ]
    )]
    /**
     * @throws SystemException
     */
    public function actionMinus(string $isbn, int $all = 0): ?int
    {
        if ($book = Books::findOne(['isbn' => $isbn]))
            try {
                return CartItems::minusItemFromCart($book, Yii::$app->user->id, 1, !!$all);
            } catch (\Exception|Throwable $exception) {
                throw new SystemException('Не удалось удалить книгу');
            }
        else
            throw new SystemException('Книга не найдена');
    }

    #[OA\Post(
        path: '/cart/clear',
        summary: 'Полная очистка корзины',
        security: [
            ["bearerAuth" => []]
        ],
        tags: ['cart'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(example: true)
            ),
            new OA\Response(
                response: 401,
                description: 'Ошибка',
                content: new OA\JsonContent(ref: AuthErrorResponse::class),
            )
        ]
    )]
    public function actionClear(): true
    {
        CartItems::clearCart(Yii::$app->user->id);
        return true;
    }

}
