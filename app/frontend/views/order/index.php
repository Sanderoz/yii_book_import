<?php

use common\components\enums\DeliveryType;
use common\components\enums\PaymentType;
use common\models\CartItems;
use common\models\OrderDeliveries;
use common\models\OrderPayment;
use common\models\Orders;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var CartItems[] $cartItems
 * @var array $paymentTypes
 * @var Orders $orderModel
 * @var OrderPayment $paymentModel
 * @var OrderDeliveries $deliveryModel
 * @var View $this
 */

$this->title = 'Оформление заказа';

$this->registerCss('
    .text-right {
        text-align: right;
    }
');
?>

<div class="order-block">
    <h1 class="mb-5 mt-4"><?= $this->title ?></h1>
    <div class="row">
        <div class="col-12">
            <table class="w-100 table table-striped">
                <thead>
                <tr>
                    <th>isbn</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Стоимость</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($cartItems as $cartItem) { ?>
                    <tr>
                        <td><?= $cartItem->book_isbn ?></td>
                        <td><?= $cartItem->book->title ?></td>
                        <td><?= Yii::$app->formatter->asDecimal($cartItem->book->price / 100, 2) ?> &#8381;</td>
                        <td><?= $cartItem->count ?></td>
                        <td><?= Yii::$app->formatter->asDecimal($cartItem->count * $cartItem->book->price / 100, 2) ?>
                            &#8381;
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3">
                    </td>
                    <td class="text-right">
                        Итого:
                    </td>
                    <td>
                        <?= Yii::$app->formatter->asDecimal(CartItems::getCartCost() / 100, 2) ?> &#8381;
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <div class="col-12">
            <?php
            $form = ActiveForm::begin([
                'action' => Url::to('/order/create')
            ]) ?>
            <div class="form-group mb-3">
                <?= $form->field($deliveryModel, 'type')->dropDownList([
                    DeliveryType::PICKUP->value => DeliveryType::PICKUP_NAME
                ]) ?>
            </div>
            <div class="form-group mb-4">
                <?= $form->field($paymentModel, 'type')->dropDownList([
                    PaymentType::SBP->value => PaymentType::SBP_NAME
                ]) ?>
            </div>

            <div class="form-group">
                <div class="col-lg-offset-1 col-lg-11">
                    <?= Html::submitButton('Создать заказ', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
