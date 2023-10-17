<?php

namespace common\models;

use app\components\exceptions\FileException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property int $created_at
 * @property string $name
 * @property string $hash
 * @property string|null $original_name
 * @property string $path
 * @property string $full_path Ссылка на внутреннее хранилище
 * @property string|null $s3path Ссылка на хранилище s3
 *
 * @property BookCategories[] $bookCategory
 */
class Files extends \yii\db\ActiveRecord
{
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%files}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['path'], 'required'],
            [['created_at'], 'integer'],
            ['hash', 'string'],
            ['hash', 'unique'],
            [['name', 'original_name', 'path', 's3path', 'full_path'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
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
            'created_at' => 'Дата создания',
            'name' => 'Наименование',
            'original_name' => 'Искомое наименование',
            'path' => 'Путь до файла',
            'full_path' => 'Путь до файла',
            's3path' => 'S3path',
        ];
    }

    /**
     * @throws FileException
     */
    public function upload()
    {
        if ($file = UploadedFile::getInstance($this, 'imageFile') and file_exists($file->tempName)) {
            $hash_file = md5_file($file->tempName);
            if ($db_image = $this->getImg($hash_file))
                return $db_image->id;

            $this->original_name = $file->name;
            $this->hash = $hash_file;
            $this->path = Files::getFilePath() . '/' . $hash_file . '.' . $file->extension;

            if ($file->saveAs($this->path) and $this->validate())
                $this->save();
            else
                throw new FileException('Не удалось сохранить файл');

            return $this->id;
        }
        return null;
    }

    /**
     * @param $url
     * @return int|bool
     */
    public static function saveFileByUrl($url): int|bool
    {
        try {
            $hash = md5_file($url);
            if ($fileModel = Files::find()->where(['hash' => $hash])->one())
                return $fileModel->id;

            $imageContent = file_get_contents($url);
            $filename = $hash . '.' . pathinfo($url, PATHINFO_EXTENSION);

            FileHelper::createDirectory(Files::getFilePath() . '/books/');
            $filePath = Files::getFilePath() . '/books/' . $filename;
            if (!file_put_contents($filePath, $imageContent))
                return false;

            unset($imageContent);
            $fileModel = new Files();
            $fileModel->original_name = pathinfo(basename($url), PATHINFO_FILENAME);
            $fileModel->hash = $hash;
            $fileModel->path = '/uploads/books/' . $filename;
            $fileModel->full_path = $filePath;
            $fileModel->s3path = $url;
            if ($fileModel->validate() and $fileModel->save())
                return $fileModel->id;
        } catch (\Exception $exception) {

        }

        return false;
    }

    /**
     * @param $hash
     * @return Files|null
     */
    private function getImg($hash): ?Files
    {
        return Files::find()->where(['hash' => $hash])->one();
    }

    public static function getFilePath(string $name = ''): false|string
    {
        return \Yii::getAlias('@commonUploads') . $name;
    }

}
