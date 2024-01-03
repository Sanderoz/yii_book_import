<?php
namespace common\components\exceptions;

use yii\base\Exception;

class DTOException extends Exception
{
    public function getName(): string
    {
        return 'Ошибка при передаче значений';
    }
}