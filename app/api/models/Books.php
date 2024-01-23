<?php

namespace api\models;

use Yii;
use OpenApi\Attributes as OA;

class Books extends \common\models\Books
{
    public static function find(): BooksQuery
    {
        return new BooksQuery(get_called_class());
    }
}
