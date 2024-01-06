<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $payment_id
 * @property string $bank Банк, проводивший операцию
 * @property string|null $qr_id Идентификатор QR-кода
 * @property string|null $order_id Номер заказа в платежной системе. Уникален в пределах системы.
 * @property string|null $form_url URL платежной формы, на который надо перенаправить браузер клиента
 * @property string|null $payload Содержимое зарегистрированного в СБП QRкода
 * @property string|null $qr_status Состояние запроса QR_кода
 * @property string|null $error_message Описание ошибки
 * @property string|null $error_code Код ошибки
 * @property int $created_at
 * @property int $updated_at
 *
 * @property OrderPayment $payment
 */
class OrderPaymentSbp extends BaseModel
{
    public static function tableName(): string
    {
        return '{{%order_payment_sbp}}';
    }

    public function rules(): array
    {
        return [
            [['payment_id', 'bank', 'created_at', 'updated_at'], 'required'],
            [['payment_id', 'created_at', 'updated_at'], 'integer'],
            [['bank', 'qr_id', 'order_id', 'form_url', 'payload', 'qr_status', 'error_message', 'error_code'], 'string', 'max' => 255],
            [['payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderPayment::class, 'targetAttribute' => ['payment_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => time(),
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'payment_id' => 'Payment ID',
            'bank' => 'Bank',
            'qr_id' => 'Qr ID',
            'order_id' => 'Order ID',
            'form_url' => 'Form Url',
            'payload' => 'Payload',
            'qr_status' => 'Qr Status',
            'error_message' => 'Error Message',
            'error_code' => 'Error Code',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getPayment(): ActiveQuery
    {
        return $this->hasOne(OrderPayment::class, ['id' => 'payment_id']);
    }
}
