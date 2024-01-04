<?php
namespace common\components\exceptions;

use yii\base\Exception;

class OrderException extends Exception
{
    public function getName(): string
    {
        return 'Ошибка присоздании заказа';
    }
}