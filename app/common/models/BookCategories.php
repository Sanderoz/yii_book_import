<?php

namespace common\models;

use app\components\exceptions\FileException;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "book_categories".
 *
 * @property int $id
 * @property int|null $image
 * @property int|null $parent Родительская категория
 * @property string $name
 *
 * @property BookBelongsCategories[] $bookBelongsCategories
 * @property Books[] $books
 * @property Files $file
 *
 * @property string $img_path
 */
class BookCategories extends BaseModel
{
    public string $img_path = '';
    private static array $categories = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book_categories}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['image', 'parent'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            ['parent', 'default', 'value' => 0],
            [['image'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['image' => 'id']],
            ['parent', 'validateParent']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Изображение',
            'parent' => 'Родительская категория',
            'name' => 'Наименование',
        ];
    }

    /**
     * @throws Exception
     */
    public function validateParent($attribute, $params): void
    {
        if (!$this->isNewRecord) {
            $check = \Yii::$app->db->createCommand('
                      WITH RECURSIVE category_hierarchy AS (
                      SELECT id, name, parent
                      FROM book_categories
                      WHERE id = :current
                    
                      UNION ALL
                    
                      SELECT c.id, c.name, c.parent
                      FROM book_categories c
                      INNER JOIN category_hierarchy ch ON c.parent = ch.id
                    )
                    SELECT id
                    FROM book_categories
                    WHERE :parent_id IN (SELECT id FROM category_hierarchy)
                ')
                ->bindValue(':current', $this->id)
                ->bindValue(':parent_id', $this->parent)
                ->queryOne();

            if ($check)
                $this->addError($attribute, 'Родителем не может быть дочерний элемент');
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->img_path = empty($this->file->path) ? Files::getFilePath('No-Image.png') : $this->file->path;
    }

    /**
     * Gets query for [[Books]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Books::class, ['isbn' => 'book'])
            ->viaTable('book_belongs_category', ['category' => 'id']);
    }

    /**
     * Gets query for [[Image0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(Files::class, ['id' => 'image']);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function availableParents(): array
    {
        if ($this->isNewRecord) {
            $parents = ArrayHelper::map(BookCategories::find()->select(['id', 'name'])->orderBy('name DESC')->all(), 'id', 'name');
        } else {
            $categories = \Yii::$app->db->createCommand('
                      WITH RECURSIVE category_hierarchy AS (
                      SELECT id, name, parent
                      FROM book_categories
                      WHERE id = :current
                    
                      UNION ALL
                    
                      SELECT c.id, c.name, c.parent
                      FROM book_categories c
                      INNER JOIN category_hierarchy ch ON c.parent = ch.id
                    )
                    SELECT id, name
                    FROM book_categories
                    WHERE id NOT IN (SELECT id FROM category_hierarchy)
                    ORDER BY name DESC
                ')
                ->bindValue(':current', $this->id)
                ->queryAll();

            $parents = ArrayHelper::map($categories, 'id', 'name');
        }

        $parents[0] = 'Главная категория';
        return $parents;
    }

    public static function getCategory($name)
    {
        if (isset(self::$categories[$name])) {
            return self::$categories[$name];
        }

        if (!$category = self::find()->where(['name' => $name])->one()) {
            $category = new BookCategories();
            $category->name = $name;
            $category->image = null;
            if ($category->validate())
                $category->save();
            else
                throw new Exception($category->getValidateErrorsAsString());
        }

        self::$categories[$category->name] = $category->id;
        return $category->id;
    }

    /**
     * @return array
     */
    public static function getList(): array
    {
        return ArrayHelper::map(self::find()->select(['id', 'name'])->all(), 'id', 'name');
    }
}
