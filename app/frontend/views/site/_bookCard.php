<?php

use \yii\helpers\Html;

/**
 * @var \common\models\Books $model
 */
?>

<img src="<?= $model->img_path ?>" class="w-100">
<p class="text-center">
    <?= Html::a($model->title, ['/book', 'isbn' => $model->isbn], ['class' => 'text-dark']) ?>
</p>
