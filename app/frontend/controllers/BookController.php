<?php

namespace frontend\controllers;

use common\models\Books;
use \yii\web\Controller;

class BookController extends Controller
{
    public function actionIndex($isbn)
    {
        return $this->render('index', [
            'book' => Books::find()->published()->where(['isbn' => $isbn])->one()
        ]);
    }
}