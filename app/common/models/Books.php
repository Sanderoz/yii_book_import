<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%books}}".
 *
 * @property int $pageCount Количество страниц
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $image
 * @property string $publishedDate Дата публикации
 * @property string $isbn
 * @property string $title Заголовок
 * @property string $shortDescription
 * @property string|null $longDescription
 *
 * @property Authors[] $authors
 * @property BookCategories[] $categories
 * @property Files $file
 * @property string $img_path
 */
class Books extends BaseModel
{
    public string $img_path = '';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%books}}';
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

    public function afterFind()
    {
        parent::afterFind();
        $this->img_path = empty($this->file->path) ? Files::getFilePath('No-Image.png') : $this->file->path;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pageCount', 'status', 'isbn', 'title'], 'required'],
            [['pageCount', 'created_at', 'updated_at', 'image'], 'integer'],
            [['publishedDate'], 'safe'],
            [['shortDescription', 'longDescription', 'status'], 'string'],
            [['isbn', 'title'], 'string', 'max' => 255],
            [['isbn'], 'unique'],
            [['image'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['image' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pageCount' => 'Количество страниц',
            'status' => 'Статус',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'publishedDate' => 'Дата публикации',
            'isbn' => 'Isbn',
            'title' => 'Заголовок',
            'shortDescription' => 'Краткое описание',
            'longDescription' => 'Полное описание',
            'image' => 'Изображение',
        ];
    }

    /**
     * @return array
     */
    public function getSelectedAuthors(): array
    {
        return BookAuthors::find()->select('author')->where(['book' => $this->isbn])->column();
    }

    /**
     * @return array
     */
    public function getSelectedCategories(): array
    {
        return BookBelongsCategories::find()->select('category')->where(['book' => $this->isbn])->column();
    }

    /**
     * Gets query for [[Authors]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Authors::class, ['id' => 'author'])->viaTable('{{%book_authors}}', ['book' => 'isbn']);
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(BookCategories::class, ['id' => 'category'])->viaTable('{{%book_belongs_category}}', ['book' => 'isbn']);
    }

    public function getFile()
    {
        return $this->hasOne(Files::class, ['id' => 'image']);
    }

}
