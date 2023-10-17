<?php

namespace common\models;

use yii\db\Exception;

/**
 * @property string $book ISBN книги
 * @property int $author Id автора
 */
class BookAuthors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book_authors}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book', 'author'], 'required'],
            [['author'], 'integer'],
            [['book'], 'string', 'max' => 255],
            [['book', 'author'], 'unique', 'targetAttribute' => ['book', 'author']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => Authors::class, 'targetAttribute' => ['author' => 'id']],
            [['book'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['book' => 'isbn']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'book' => 'Book',
            'author' => 'Author',
        ];
    }

    /**
     * @param string $isbn
     * @param array $authors
     * @return bool
     * @throws Exception
     */
    public static function updateAuthorRelations(string $isbn, array $authors): bool
    {
        $result = 1;
        BookAuthors::deleteAll(['book' => $isbn]);
        foreach ($authors as $author) {
            $records[] = [
                'book' => $isbn,
                'author' => (int)$author
            ];
        }
        if (!empty($records))
            $result = \Yii::$app->db->createCommand()->batchInsert(BookAuthors::tableName(), ['book', 'author'], $records)->execute();
        return $result > 0;
    }
}
