<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\BookCategories $model */

$this->title = 'Создание категории';
$this->params['breadcrumbs'][] = ['label' => 'Book Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
