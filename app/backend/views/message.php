<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Messages $model */
/** @var ActiveForm $form */
?>
<div class="message">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'user') ?>
        <?= $form->field($model, 'created_at') ?>
        <?= $form->field($model, 'phone') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'message') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- message -->
