<?php

namespace common\models;

use common\components\dto\OrderItemDTO;
use common\components\enums\OrderStatus;
use common\components\enums\PaymentStatus;
use common\components\enums\PaymentType;
use common\components\exceptions\OrderException;
use common\components\interfaces\payment\OrderPaymentDataInterface;
use common\components\interfaces\payment\OrderPaymentInterface;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\Json;

/**
 * @property int $id
 * @property string $number
 * @property int $user_id
 * @property int $status OrderStatus
 * @property int $total_price Общая стоимость, выраженная в копейках
 * @property int $created_at
 * @property int $updated_at
 * @property string $items
 *
 * @property CartItems[] $cartItems
 * @property Books[] $cartItemsProducts
 * @property User $user
 * @property OrderPayment $payment
 * @property OrderDeliveries $delivery
 */
class Orders extends BaseModel implements OrderPaymentInterface
{
    // Просто рандомный префикс для номера заказа
    const NUMBER_PREFIX = 'N';
    /**
     * @var OrderItemDTO[]
     */
    public array $itemsDTO = [];

    public static function tableName(): string
    {
        return '{{%orders}}';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'status', 'total_price'], 'required'],
            [['user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['number'], 'string', 'max' => 255],
            [['items'], 'validateJson'],
            ['total_price', 'integer', 'min' => 100],
            ['status', 'in', 'range' => OrderStatus::getValues()],
            [['number'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'number' => 'Номер заказа',
            'user_id' => 'User ID',
            'status' => 'Статус',
            'total_price' => 'Итоговая цена',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
        ];
    }

    public function afterSave($insert, $changedAttributes): void
    {
        if (empty($this->number)) {
            $this->number = self::NUMBER_PREFIX . $this->id;
            $this->save();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function updateStatus(OrderStatus $status): void
    {
        // TODO: дописать
    }

    /**
     * @throws OrderException
     * @throws Exception
     */
    public static function crateOrder(int $user_id): self
    {
        $model = new self([
            'user_id' => $user_id,
            'status' => OrderStatus::NEW->value,
            'total_price' => CartItems::getCartCost($user_id)
        ]);
        $model->setItems();
        if (!$model->save())
            throw new OrderException('Ошибка при создании заказа');

        return $model;
    }

    /**
     * Сохранение элементов корзины в заказе
     * @param array $items
     * @return void
     * @throws OrderException
     * @throws \Exception
     */
    public function setItems(array $items = []): void
    {
        if (empty($items))
            if (empty($this->cartItems))
                throw new OrderException('Корзина пуста');
            else
                foreach ($this->cartItems as $cartItem)
                    $this->itemsDTO[] = new OrderItemDTO(
                        [
                            'bookIsbn' => $cartItem->book_isbn,
                            'price' => $cartItem->book->price,
                            'count' => $cartItem->count
                        ]);
        else
            foreach ($items as $item)
                $this->itemsDTO[] = new OrderItemDTO($item);
    }

    /**
     * @throws \Exception
     */
    public function afterFind(): void
    {
        foreach (Json::decode($this->items) as $item)
            $this->itemsDTO[] = new OrderItemDTO($item);

        parent::afterFind();
    }

    public function beforeSave($insert)
    {
        $items = [];
        foreach ($this->itemsDTO as $item)
            $items[] = $item->getData();

        $this->items = Json::encode($items);

        return parent::beforeSave($insert);
    }

    public function validateJson($attribute, $params)
    {
        if (!json_validate($this->$attribute))
            $this->addError($attribute, 'Invalid JSON format.');
    }

    /**
     * @throws OrderException
     */
    public function getPaymentModel(?PaymentType $paymentType = null): OrderPaymentDataInterface
    {
        if ($this->isNewRecord)
            throw new OrderException('Что то пошло не так');

        if (!empty($this->payment))
            return $this->payment;

        if ($paymentType === null)
            throw new OrderException('Что то пошло не так');

        switch ($paymentType) {
            case PaymentType::SBP:
                $payment = new OrderPayment([
                    'order_id' => $this->id,
                    'amount' => $this->total_price,
                    'type' => PaymentType::SBP->value,
                    'status' => PaymentStatus::NEW->value
                ]);
                $payment->save();
                return $payment;
            case PaymentType::CARD:
            default:
                throw new OrderException('Что то пошло не так');
        }
    }

    public function getCartItemsProducts(): ActiveQuery
    {
        return $this->hasMany(Books::class, ['isbn' => 'book_isbn'])
            ->viaTable(CartItems::tableName(), ['user_id' => 'user_id']);
    }

    public function getCartItems()
    {
        return $this->hasMany(CartItems::class, ['user_id' => 'user_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getPayment(): ActiveQuery
    {
        return $this->hasOne(OrderPayment::class, ['order_id' => 'id']);
    }

    public function getOrderNumber(): string
    {
        return $this->number;
    }

    public function getOrderId(): int
    {
        return $this->id;
    }

    public function getTotalPrice(): int
    {
        return $this->total_price;
    }

    public function getDelivery(): ActiveQuery
    {
        return $this->hasOne(OrderDeliveries::tableName(), ['order_id' => 'id']);
    }
}
