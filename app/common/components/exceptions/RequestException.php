<?php
namespace common\components\exceptions;

use yii\base\Exception;

class RequestException extends Exception
{
    public function getName(): string
    {
        return 'Ошибка при отправке запроса на сторонний сервис';
    }
}