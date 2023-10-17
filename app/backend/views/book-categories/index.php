<?php

use common\models\BookCategories;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var array $data */

$this->title = 'Категории книг';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss("
    .book-category-name {
        color: black;
        text-decoration: none;
        padding: 6px 10px;
        display: block;
        border-left: 1px dashed #b5adad;
        /* width: 100%; */
        border-bottom: 1px dashed #b5adad;
    }
    .book-category-name:hover{
       background: #e3e3e3;
    }
");

function printTree($array, $offset)
{
    foreach ($array as $item) {
        echo '<div style="margin-left:' . ($offset * 10) . 'px">'
            . Html::a($item['name'], ['/book-categories/view', 'id' => $item['id']], ['class' => 'book-category-name'])
            . '</div>';

        if (!empty($item['childs']))
            printTree($item['childs'], $offset + 1);
    }
}

?>
<div class="book-category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать категорию', ['view'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    printTree($data, 0);
    ?>
</div>
