<?php

namespace common\models;

use Throwable;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\Expression;
use yii\db\StaleObjectException;

/**
 * @property int $user_id
 * @property string $book_isbn
 * @property int $count
 *
 * @property Books $book
 * @property User $user
 */
class CartItems extends BaseModel
{
    public static function tableName(): string
    {
        return '{{%cart_items}}';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'book_isbn'], 'required'],
            [['user_id'], 'integer'],
            ['count', 'integer', 'min' => 1],
            ['count', 'default', 'value' => 1],
            ['book_isbn', 'string', 'max' => 255],
            [['book_isbn', 'user_id'], 'unique', 'targetAttribute' => ['book_isbn', 'user_id']],
            ['book_isbn', 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['book_isbn' => 'isbn']],
            ['user_id', 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * Очистка корзины
     * @param int|null $userId
     * @return int
     */
    public static function clearCart(?int $userId = null): int
    {
        if ($userId === null)
            $userId = Yii::$app->user->id;

        return self::deleteAll(['user_id' => $userId]);
    }

    /**
     * @param int|null $userId
     * @return false|int
     */
    private static function getUserId(?int $userId = null): false|int
    {
        if ($userId !== null)
            return $userId;

        if (Yii::$app->user->isGuest)
            return false;

        return Yii::$app->user->id;
    }

    /**
     * Добавление/увеличение количества товара в корзину
     * @param Books $book
     * @param User $user
     * @param int $count
     * @return int
     * @throws Exception
     */
    public static function addItemInCart(Books $book, ?int $userId = null, int $count = 1): ?int
    {
        if (!($userId = self::getUserId($userId)))
            return null;

        Yii::$app->db->createCommand()->upsert(self::tableName(),
            [
                'user_id' => $userId,
                'book_isbn' => $book->isbn,
                'count' => 1
            ], [
                'count' => new Expression('count + :count'),
            ])
            ->bindParam('count', $count)
            ->execute();

        return self::findOne(['user_id' => $userId, 'book_isbn' => $book->isbn])->count;
    }

    /**
     * Уменьшение количества/удаление товара из корзины
     * @param Books $book
     * @param User $user
     * @param int $count
     * @param bool $all
     * @return int|null
     * @throws StaleObjectException
     * @throws Throwable
     */
    public static function minusItemFromCart(Books $book, ?int $userId = null, int $count = 1, bool $all = false): ?int
    {
        if (!($userId = self::getUserId($userId)))
            return null;

        if (empty($model = self::findOne([
            'book_isbn' => $book->isbn,
            'user_id' => $userId
        ])))
            return null;

        $model->count -= $count;

        if ($all || $model->count < 1) {
            $model->delete();
            return null;
        }

        $model->save();
        return $model->count;
    }

    /**
     * Получение стоимости корзины
     * @param int|null $userId
     * @return int
     */
    public static function getCartCost(?int $userId = null): int
    {
        if (!($userId = self::getUserId($userId)))
            return 0;

        $cartCost = 0;
        foreach (self::getUserItems($userId) as $cartItems)
            $cartCost += $cartItems->count * $cartItems->book->price;

        return $cartCost;
    }

    /**
     * Получить товары корзины
     * @param int|null $userId
     * @return CartItems[]
     */
    public static function getUserItems(?int $userId = null): array
    {
        if (!($userId = self::getUserId($userId)))
            return [];

        return self::find()->where(['user_id' => $userId])->all();
    }

    /**
     * Получение isbn всех книг, находящихся в корзине текущего пользователя
     * @param int|null $userId
     * @return array
     */
    public static function getUserItemsAsArray(?int $userId = null): array
    {
        if (!($userId = self::getUserId($userId)))
            return [];

        return self::find()
            ->select('book_isbn')
            ->where(['user_id' => $userId])
            ->asArray()
            ->column();
    }

    public function getBook(): ActiveQuery
    {
        return $this->hasOne(Books::class, ['isbn' => 'book_isbn']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
