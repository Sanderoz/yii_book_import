<?php

namespace common\models;

use common\components\enums\BookStatus;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Books;

/**
 * BookQuery represents the model behind the search form of `common\models\Book`.
 */
class BooksSearch extends Books
{
    public $parent;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pageCount', 'status', 'created_at', 'updated_at'], 'integer'],
            [['isbn', 'title', 'parent'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Books::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Settings::getPageCount()
            ]
        ]);

        $this->load($params);
        // почему-то не подгружается через load
        $this->parent = $params['parent'] ?? null;

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'pageCount' => $this->pageCount,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//            'publishedDate' => $this->publishedDate,
        ]);

        if (!is_null($this->parent))
            $query->andWhere(
                [
                    'EXISTS',
                    BookBelongsCategories::find()
                        ->select('book')
                        ->where(['category' => $this->parent])
                        ->andWhere('book = books.isbn')
                ]
            );

        $query->published();

        $query->andFilterWhere(['like', 'isbn', $this->isbn])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }

}
