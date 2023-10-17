<?php

namespace common\models;

use yii\helpers\ArrayHelper;

/**
 * @property int $id
 * @property string $name
 *
 * @property Books[] $books
 */
class Authors extends BaseModel
{
    private static array $authors = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%authors}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Books]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Books::class, ['isbn' => 'book'])->viaTable('book_authors', ['author' => 'id']);
    }

    /**
     * @param string $name
     * @return int|mixed
     * @throws \Exception
     */
    public static function getAuthor(string $name)
    {
        if (isset(self::$authors[$name]))
            return self::$authors[$name];

        if (!$author = self::find()->where(['name' => $name])->one()) {
            $author = new Authors();
            $author->name = $name;
            if ($author->validate())
                $author->save();
            else
                throw new \Exception($author->getValidateErrorsAsString());
        }

        self::$authors[$author->name] = $author->id;
        return $author->id;
    }

    public static function getList(): array
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }
}
