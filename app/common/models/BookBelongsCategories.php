<?php

namespace common\models;

/**
 * @property int $category ID категории, к которой относится книга
 * @property string $book Isbn книги
 */
class BookBelongsCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book_belongs_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category', 'book'], 'required'],
            [['category'], 'integer'],
            [['book'], 'string', 'max' => 255],
            [['category', 'book'], 'unique', 'targetAttribute' => ['category', 'book']],
            [['book'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['book' => 'isbn']],
            [['category'], 'exist', 'skipOnError' => true, 'targetClass' => BookCategories::class, 'targetAttribute' => ['category' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category' => 'Category',
            'book' => 'Book',
        ];
    }

    /**
     * @param string $isbn
     * @param array $categories
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function updateCategoriesRelations(string $isbn, array $categories): bool
    {
        $result = 1;
        BookBelongsCategories::deleteAll(['book' => $isbn]);
        $records = [];
        foreach ($categories as $category) {
            $records[] = [
                'book' => $isbn,
                'category' => (int)$category
            ];
        }
        if (!empty($records))
            $result = \Yii::$app->db->createCommand()->batchInsert(BookBelongsCategories::tableName(), ['book', 'category'], $records)->execute();
        return $result > 0;
    }
}
