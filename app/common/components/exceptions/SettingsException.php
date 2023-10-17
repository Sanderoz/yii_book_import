<?php
namespace common\components\exceptions;

use yii\base\Exception;

class SettingsException extends Exception
{
    public function getName(): string
    {
        return 'Ошибка настроек';
    }
}