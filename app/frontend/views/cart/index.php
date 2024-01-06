<?php

/**
 * @var CartItems[] $cartItems
 * @var View $this
 */

use common\models\CartItems;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Корзина';

$this->registerCss('
    .cart-header {
        border-bottom: 2px solid black;
        padding-bottom: 5px;
    }

    .cart-footer{
        border-top: 2px solid black;
        padding-top: 5px;
    }

    .text-right {
        text-align: right;
    }
');
?>
<div class="cart-block">
    <h1 class="mb-5 mt-4"><?= $this->title ?></h1>
    <?php
    if (empty($cartItems)) {
        ?>
        <h3>Корзина пуста</h3>
        <?php
    } else { ?>
        <div class="row mb-3 cart-header text-center">
            <div class="col-2">isbn</div>
            <div class="col-4">Название</div>
            <div class="col-2">Цена</div>
            <div class="col-2">Количество</div>
            <div class="col-2">Стоимость</div>
        </div>
        <?php
        foreach ($cartItems as $item)
            echo $this->render('_item', ['item' => $item]);
        ?>
        <div class="row mt-3 cart-footer">
            <div class="col-4 offset-8 text-right">
                Итого: <?= Yii::$app->formatter->asDecimal(CartItems::getCartCost() / 100, 2) ?> &#8381;
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 text-right">
                <?= Html::a('Заказать', Url::to('/order'), ['class' => 'btn btn-success']); ?>
            </div>
        </div>
        <?php
    }
    ?>

</div>
