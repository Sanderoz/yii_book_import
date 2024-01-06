<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * @property string $book_isbn
 * @property int $order_id
 * @property int $price Стоимость товара на момент оформления заказа
 * @property int $count Количество заказанных книг
 *
 * @property Books $book
 */
class OrderItems extends BaseModel
{
    public static function tableName(): string
    {
        return '{{%order_items}}';
    }

    public function rules(): array
    {
        return [
            [['book_isbn', 'order_id'], 'required'],
            [['order_id', 'price', 'count'], 'integer'],
            [['book_isbn'], 'string', 'max' => 255],
            [['book_isbn', 'order_id'], 'unique', 'targetAttribute' => ['book_isbn', 'order_id']],
            [['book_isbn'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['book_isbn' => 'isbn']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::class, 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    public function getBook(): ActiveQuery
    {
        return $this->hasOne(Books::class, ['isbn' => 'book_isbn']);
    }

}
