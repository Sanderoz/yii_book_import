<?php
namespace common\components\exceptions;

use yii\base\Exception;

class SystemException extends Exception
{
    public function getName(): string
    {
        return 'Ошибка в работе приложения';
    }
}