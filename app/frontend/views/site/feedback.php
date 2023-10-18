<?php

use \yii\widgets\MaskedInput;

/** @var yii\web\View $this
 * @var yii\bootstrap5\ActiveForm $form
 * @var \frontend\models\FeedbackForm $model
 */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Форма обратной связи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
                    'mask' => '+9 (999) 999-99-99',
                ])->textInput(['placeholder' => '+7 (123) 456-78-90']); ?>

                <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
