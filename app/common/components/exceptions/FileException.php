<?php
namespace app\components\exceptions;

use yii\base\Exception;

class FileException extends Exception
{
    public function getName(): string
    {
        return 'Ошибка при сохранении файла';
    }
}