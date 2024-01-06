<?php

namespace frontend\controllers;

use common\components\enums\DeliveryStatus;
use common\models\Books;
use common\models\CartItems;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Site controller
 */
class CartController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function actionAdd(string $isbn)
    {
        if ($book = Books::findOne(['isbn' => $isbn]))
            CartItems::addItemInCart($book);

        return $this->redirect(Url::to(Yii::$app->request->referrer));
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionMinus(string $isbn)
    {
        if ($book = Books::findOne(['isbn' => $isbn]))
            CartItems::minusItemFromCart($book);

        return $this->redirect(Url::to(Yii::$app->request->referrer));
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'cartItems' => CartItems::getUserItems()
        ]);
    }

}
