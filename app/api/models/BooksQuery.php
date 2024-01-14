<?php

namespace api\models;

use common\components\enums\BookStatus;
use Yii;
use yii\db\ActiveQuery;


class BooksQuery extends ActiveQuery
{
    public function published(): BooksQuery
    {
        return $this->andWhere(['status' => BookStatus::PUBLISH->value]);
    }
}
