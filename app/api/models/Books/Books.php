<?php

namespace api\models\Books;

use api\models\BooksQuery;

class Books extends \common\models\Books
{
    public static function find(): BooksQuery
    {
        return new BooksQuery(get_called_class());
    }
}
