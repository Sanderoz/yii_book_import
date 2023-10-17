<?php

namespace common\handlers;

use common\models\Files;
use Yii;
use yii\base\BaseObject;

class FileImportHandler extends BaseObject implements \yii\queue\JobInterface
{
    public string $book;
    public string $url;

    /**
     * @throws \yii\db\Exception
     */
    public function execute($queue)
    {
        if ($file_id = Files::saveFileByUrl($this->url)) {
            Yii::$app->db->createCommand('
                    UPDATE books
                    SET image = :image
                    WHERE isbn = :isbn
                ')
                ->bindValue(':image', $file_id)
                ->bindValue(':isbn', $this->book)
                ->query();
        }
    }
}