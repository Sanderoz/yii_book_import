<?php

namespace common\components\helpers;

class AppHelper
{
    public function isDev()
    {
        return (defined('YII_ENV') && YII_ENV == 'dev');
    }
}