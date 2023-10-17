<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\Authors;
use common\components\enums\BookStatus;
use \kartik\select2\Select2;
use \common\models\BookCategories;


/**
 * @var yii\web\View $this
 * @var common\models\Books $model
 * @var yii\widgets\ActiveForm $form
 * @var \common\models\Files $file
 */

?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::img($model->img_path, ['alt' => $model->file->original_name ?? '', 'width' => 200, 'class' => 'mb-3 mt-3']); ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'shortDescription')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'longDescription')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'pageCount')->textInput() ?>
    <?= $form->field($model, 'status')->dropDownList(BookStatus::getList()) ?>

    <?= $form->field($model, 'publishedDate')->textInput() ?>

    <?= '<label class="control-label">Авторы</label>' ?>
    <?= Select2::widget([
        'name' => 'Authors[]',
        'value' => $model->getSelectedAuthors(),
        'data' => Authors::getList(),
        'options' => ['placeholder' => 'Выбрать автора', 'multiple' => true],
        'pluginOptions' => [
            'allowClear' => true,
            'tokenSeparators' => [','],
            'tags' => true
        ],
    ]); ?>

    <?= '<label class="control-label">Категории</label>' ?>
    <?= Select2::widget([
        'name' => 'BookCategories[]',
        'value' => $model->getSelectedCategories(),
        'data' => BookCategories::getList(),
        'options' => ['placeholder' => 'Выбрать категорию', 'multiple' => true],
        'pluginOptions' => [
            'allowClear' => true,
            'tokenSeparators' => [','],
            'tags' => true
        ],
    ]); ?>

    <?= $form->field($file, 'imageFile')->fileInput(['class' => 'mb-3 mt-3']); ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
