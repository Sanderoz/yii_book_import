<?php

namespace frontend\controllers;

use common\models\Books;
use common\models\CartItems;
use yii\filters\HttpCache;
use \yii\web\Controller;

class BookController extends Controller
{
    public function behaviors(): array
    {
        return [
            'httpCache' => [
                'class' => HttpCache::class,
                'only' => ['index'],
                'lastModified' => function ($action, $params) {
                    return Books::find()
                        ->select('updated_at')
                        ->published()
                        ->where(['isbn' => \Yii::$app->request->get('isbn')])
                        ->scalar();
                }
            ],
        ];
    }

    public function actionIndex(string $isbn): string
    {
        return $this->render('index', [
            'book' => Books::find()->published()->where(['isbn' => $isbn])->one(),
            'cartItems' => CartItems::getUserItemsAsArray()
        ]);
    }
}