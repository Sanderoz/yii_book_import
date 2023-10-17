<?php

use common\models\Books;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\BooksQuery $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить книгу', ['view'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => function (Books $model) {

                    return empty($model->imageModel) ? '' : '<img src="' . $model->imageModel->path . '" alt="' . $model->imageModel->original_name . '">';
                },
            ],
            [
                'attribute' => 'isbn',
                'format' => 'html',
                'value' => function (Books $model) {
                    return Html::a($model->isbn, ['/books/view/', 'id' => $model->isbn]);
                },
            ],
            'title',
            'shortDescription',
            [
                'attribute' => 'longDescription',
                'format' => 'ntext',
                'contentOptions' => ['style' => 'width:100%'],
            ],
            'pageCount',
            'status',
            [
                'label' => 'Авторы',
                'format' => 'html',
                'value' => function (Books $model) {
                    $html = '';
                    foreach ($model->authors as $author) {
                        $html .= $author->name . '<br>';
                    }
                    return $html;
                },
            ],
            [
                'label' => 'Категории',
                'format' => 'html',
                'value' => function (Books $model) {
                    $html = '';
                    foreach ($model->categories as $category) {
                        $html .= Html::a($category->name . '<br>', ['book-categories/view', 'id' => $category->id]);
                    }
                    return $html;
                },
            ],
            'publishedDate',
            [
                'class' => ActionColumn::class,
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="fa-solid fa-trash"></i>', $url, [
                            'title' => Yii::t('yii', 'Удаление'),
                            'data-confirm' => Yii::t('yii', 'Удалить книгу?'),
                            'data-method' => 'post',
                        ]);
                    },
                ]
            ],
        ],
        'pager' => [
            'options' => ['class' => 'pagination justify-content-center'],
            'prevPageCssClass' => 'page-item',
            'nextPageCssClass' => 'page-item',
            'pageCssClass' => 'page-item',
            'activePageCssClass' => 'active',
            'prevPageLabel' => '<span class="page-link">&laquo;</span>',
            'nextPageLabel' => '<span class="page-link">&raquo;</span>',
            'disabledPageCssClass' => 'disabled',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
