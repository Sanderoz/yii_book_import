<?php

namespace common\models;

use common\components\enums\OrderStatus;
use common\components\enums\PaymentStatus;
use common\components\enums\PaymentType;
use common\components\exceptions\OrderException;
use common\components\interfaces\payment\OrderPaymentDataInterface;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $order_id
 * @property int $amount Сумма платежа
 * @property int $type 1-card/2-sbp (enum - PaymentType)
 * @property int $status From enum - PaymentStatus
 * @property int $created_at
 * @property int $updated_at
 *
 * @property OrderPaymentSbp $orderPaymentSbp
 * @property Orders $order
 * @property PaymentType $enumType
 */
class OrderPayment extends BaseModel implements OrderPaymentDataInterface
{
    public static function tableName(): string
    {
        return '{{%order_payment}}';
    }

    public function rules(): array
    {
        return [
            [['order_id', 'amount', 'type'], 'required'],
            [['order_id', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            ['amount', 'integer', 'min' => 100],
            ['type', 'in', 'range' => PaymentType::getValues()],
            ['status', 'in', 'range' => PaymentStatus::getValues()],
            ['order_id', 'exist', 'skipOnError' => true, 'targetClass' => Orders::class, 'targetAttribute' => ['order_id' => 'id']],
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

    /**
     * @throws OrderException
     */
    public function afterSave($insert, $changedAttributes): void
    {
        if (isset($changedAttributes['status']))
            $this->order->updateStatus($this->getOrderStatus());

        parent::afterSave($insert, $changedAttributes);
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'amount' => 'Amount',
            'type' => 'Способ оплаты',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Получение статуса заказа по статусу оплаты
     * @return OrderStatus
     * @throws OrderException
     */
    private function getOrderStatus(): OrderStatus
    {
        return match ($this->status) {
            PaymentStatus::NEW->value => OrderStatus::NEW,
            PaymentStatus::PROCESSED->value => OrderStatus::PROCESSED,
            PaymentStatus::CANCELED->value => OrderStatus::CANCELED,
            PaymentStatus::PAID->value => OrderStatus::PAID,
        };

        throw new OrderException('Неизвестный статус заказа');
    }

    public function getEnumType(): ?PaymentType
    {
        return PaymentType::getTypeByValue($this->type);
    }

    public function getOrder(): ActiveQuery
    {
        return $this->hasOne(Orders::class, ['id' => 'order_id']);
    }

    public function getOrderPaymentSbp(): ActiveQuery
    {
        return $this->hasOne(OrderPaymentSbp::class, ['payment_id' => 'id']);
    }

    public function getSbpPaymentModel(): ?OrderPaymentSbp
    {
        return $this->orderPaymentSbp;
    }

    /**
     * @param PaymentStatus $status
     * @param string $bank
     * @param string|null $qr_id
     * @param string|null $order_id
     * @param string|null $form_url
     * @param string|null $payload
     * @param string|null $qr_status
     * @param string|null $error_message
     * @param string|null $error_code
     * @throws OrderException|\yii\db\Exception
     */
    public function setSbpPayment(PaymentStatus $status, string $bank, ?string $qr_id = null, ?string $order_id = null, ?string $form_url = null, ?string $payload = null, ?string $qr_status = null, ?string $error_message = null, ?string $error_code = null): void
    {
        if ($this->isNewRecord)
            throw new OrderException('Не удалось создать оплату');

        $data = [
            'payment_id' => $this->id,
            'bank' => $bank,
            'qr_id' => $qr_id,
            'order_id' => $order_id,
            'form_url' => $form_url,
            'qr_status' => $qr_status
        ];

        foreach ($data as $code => $value)
            if ($value === null)
                unserialize($data[$code]);

        $data['error_message'] = $error_message;
        $data['error_code'] = $error_code;

        $model = $this->orderPaymentSbp ?? new OrderPaymentSbp();
        $model->setAttributes($data);
        if ($model->validate() and
            Yii::$app->db->createCommand()->upsert(
                OrderPaymentSbp::tableName(),
                $model->getAttributes()
            )->execute() > 0) {
            if (PaymentStatus::getByValue($this->status) !== $status) {
                $this->status = $status->value;
                $this->save();
            }

            return;
        }

        throw new OrderException('Не удалось создать оплату');
    }
}
