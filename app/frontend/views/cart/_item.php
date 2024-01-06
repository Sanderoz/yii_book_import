<?php

use common\models\CartItems;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var CartItems $item
 * @var View $this
 */

$this->registerCss('
    .cart-item a {
        color: black !important;
        text-decoration: none;
    }

    .cart-item a:hover {
        color: #a83c3c !important;
    }
');

?>
<div class="cart-item row">
    <div class="col-2">
        <?= Html::a($item->book->isbn, Url::to(['/book', 'isbn' => $item->book->isbn])); ?>
    </div>
    <div class="col-4">
        <?= $item->book->title ?>
    </div>
    <div class="col-2">
        <?= Yii::$app->formatter->asDecimal($item->book->price / 100, 2) ?> &#8381;
    </div>
    <div class="col-2 text-center">
        <?= $item->count ?>
        <?= Html::a('<i class="fa-solid fa-square-plus link-success"></i>', Url::to(['/cart/add', 'isbn' => $item->book_isbn])) ?>
        <?= Html::a('<i class="fa-solid fa-square-minus link-danger"></i>', Url::to(['/cart/minus', 'isbn' => $item->book_isbn])) ?>
    </div>
    <div class="col-2  text-right">
        <?= Yii::$app->formatter->asDecimal($item->book->price * $item->count / 100, 2) ?> &#8381;
    </div>
</div>
