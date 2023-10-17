<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var common\models\Books $model
 * @var \common\models\Files $file
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    if (!$model->isNewRecord) {
        ?>
        <p>
            <?= Html::a('Удалить', ['delete', 'isbn' => $model->isbn], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Удалить книгу?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
        <?php
    } ?>

    <?= $this->render('_form', [
        'model' => $model,
        'file' => $file
    ]) ?>

</div>
