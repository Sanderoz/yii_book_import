<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \common\models\Settings $model
 * */

$this->title = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $form = ActiveForm::begin();

    foreach ($model as $setting) {
        /**
         * @var \common\models\Settings $setting
         */
        echo '<div class="mt-4">';
        echo Html::label($setting->title, $setting->key);
        echo Html::textInput($setting->key, $setting->value, ['class' => 'form-control']);
        echo '</div>';

    }
    echo '<div class="mt-5">';
    echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']);
    echo '</div>';

    ActiveForm::end();
    ?>
</div>
