<?php

namespace common\models;

use common\components\enums\BookStatus;

/**
 * BookQuery represents the model behind the search form of `common\models\Book`.
 */
class BooksQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        $this->andWhere(['status' => BookStatus::PUBLISH->value]);
        return $this;
    }
}
