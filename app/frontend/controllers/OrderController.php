<?php

namespace frontend\controllers;

use common\components\enums\DeliveryStatus;
use common\components\enums\DeliveryType;
use common\components\enums\PaymentStatus;
use common\components\enums\PaymentType;
use common\components\exceptions\OrderException;
use common\components\services\PaymentService;
use common\models\CartItems;
use common\models\OrderDeliveries;
use common\models\OrderPayment;
use common\models\Orders;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Site controller
 */
class OrderController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    'create' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (empty($cartItems = CartItems::getUserItems()))
            return $this->redirect(Url::to(['/cart']));


        return $this->render('index', [
            'cartItems' => $cartItems,
            'paymentTypes' => PaymentType::getKeyValue(),
            'orderModel' => new Orders(),
            'paymentModel' => new OrderPayment(),
            'deliveryModel' => new OrderDeliveries()
        ]);
    }

    public function actionCreate()
    {
        $errorMessage = 'Произошла ошибка при создании заказа';
        if (empty(CartItems::getUserItems()))
            return $this->redirect(Url::to(['/cart']));

        try {
            $transaction = Yii::$app->db->beginTransaction();

            $model = Orders::crateOrder(Yii::$app->user->id);
            $delivery = new OrderDeliveries();
            $payment = new OrderPayment();

            if ($delivery->load(Yii::$app->request->post())) {
                $delivery->order_id = $model->id;
                $delivery->status = DeliveryStatus::NEW->value;
                if ($delivery->enumType === DeliveryType::PICKUP) {
                    $delivery->cost = 0;
                    $delivery->address = '';
                    $delivery->delivery_date = null;
                } else {
                    // TODO: Тут расчитываем стоимость доставки, плюсуем к заказу, сохраняем заказ
                    throw new OrderException('На данный момент доступен только самовывоз');
                }

                if (!$delivery->save())
                    throw new OrderException($errorMessage);
            } else {
                throw new OrderException($errorMessage);
            }

            if ($payment->load(Yii::$app->request->post())) {
                $payment->order_id = $model->id;
                $payment->amount = $model->total_price;
                $payment->status = PaymentStatus::NEW->value;

                $payment->validate();
                if (!$payment->save())
                    throw new Exception($errorMessage);

                if ($payment->enumType === PaymentType::SBP) {
                    // TODO: Вроде всё должно работать, но тк доступов нет, поэтому пока просто создаем заказ и оплату
//                    $service = new PaymentService($model);
//                    $service->getSbpData();
                } else {
                    throw new OrderException('На данный момент доступна только оплата по СБП');
                }
            }
            CartItems::clearCart();
            $transaction->commit();

        } catch (OrderException|Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(Url::to(['/order']));
        } catch (\yii\httpclient\Exception|\Exception $e) {
            Yii::$app->session->setFlash('error', $errorMessage);
            return $this->redirect(Url::to(['/order']));
        }

        Yii::$app->session->setFlash('success', 'Заказ успешно создан');
        return $this->redirect(Url::to(['/']));
    }

}
