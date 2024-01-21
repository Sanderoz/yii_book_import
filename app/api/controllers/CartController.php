<?php

namespace api\controllers;

use common\components\exceptions\SystemException;
use common\models\Books;
use common\models\CartItems;
use Throwable;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

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

    public function actionClear(): true
    {
        CartItems::clearCart(Yii::$app->user->id);
        return true;
    }

}
