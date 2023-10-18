<?php

use \yii\helpers\Html;

/**
 * @var \common\models\BookCategories $category
 */

?>

<div class="col-2 category_card">
    <img src="<?= $category->img_path ?>" class="w-100">
    <p class="text-center">
        <?= Html::a($category->name, ['', 'parent' => $category->id], ['class' => 'text-dark']) ?>
    </p>
</div>
