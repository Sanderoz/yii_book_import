<?php

namespace common\models;

use common\components\enums\DeliveryStatus;
use common\components\enums\DeliveryType;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "order_deliveries".
 *
 * @property int $id
 * @property int $order_id
 * @property int $type enum DeliveryType
 * @property string|null $address Адрес доставки
 * @property int|null $cost
 * @property string|null $delivery_date
 * @property int $status enum DeliveryStatus
 * @property int $updated_at
 *
 * @property Orders $order
 * @property DeliveryType $enumType
 */
class OrderDeliveries extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_deliveries}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'type', 'status'], 'required'],
            [['order_id', 'type', 'status', 'updated_at'], 'integer'],
            ['cost', 'integer', 'min' => 0],
            ['delivery_date', 'safe'],
            ['status', 'in', 'range' => DeliveryStatus::getValues()],
            ['type', 'in', 'range' => DeliveryType::getValues()],
            [['address'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::class, 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => time(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Заказ',
            'type' => 'Способ доставки',
            'address' => 'Адрес',
            'cost' => 'Стоимость доставки',
            'delivery_date' => 'Дата доставки',
            'status' => 'Статус',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Orders::class, ['id' => 'order_id']);
    }

    public function getEnumType(): ?DeliveryType
    {
        return DeliveryType::getTypeByValue($this->type);
    }
}
