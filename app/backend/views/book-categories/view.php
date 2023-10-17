<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;
use \common\models\BookCategories;

/**
 * @var yii\web\View $this
 * @var common\models\BookCategories $model
 * @var \common\models\Files $file
 */

$this->title = $model->isNewRecord ? 'Создание категории' : $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Категория', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="book-category">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    if (!$model->isNewRecord) {
        echo Html::a('Удалить', ['<span class="glyphicon glyphicon-trash"></span>', 'id' => $model->id], [
//            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Удалить категорию "' . $model->name . '" (Удаление будет успешным только при отсутсвии дочерних категорий и товаров, относящихся к данной категории)?',
                'method' => 'post',
            ],
        ]);
    }

    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);

    echo Html::img($model->img_path, ['alt' => $model->file->original_name ?? '', 'width' => 200, 'class' => 'mb-3 mt-3']);
    echo $form->field($model, 'name')->textInput(['required']);
    echo $form->field($model, 'parent')->dropDownList(BookCategories::getParents($model->id));

    echo $form->field($file, 'imageFile')->fileInput(['class' => 'mb-3 mt-3']);

    echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-block']);
    ActiveForm::end();
    ?>
</div>

