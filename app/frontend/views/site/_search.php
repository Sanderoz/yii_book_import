<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\BooksQuery $searchModel */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-search">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'class' => 'row',
        ],
    ]); ?>
    <div class="col-2">
        <?= $form->field($searchModel, 'pageCount') ?>
    </div>
    <div class="col-3">
        <?= $form->field($searchModel, 'isbn') ?>
    </div>
    <div class="col-3">
        <?= $form->field($searchModel, 'title') ?>
    </div>

    <div class="col-4 form-group d-flex align-items-end justify-content-end">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary me-3']) ?>
        <?= Html::resetButton('Сбросить фильтры', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
