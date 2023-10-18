<?php
/**
 * @var \common\models\Books $book
 *
 */
?>
<div class="col-12 mt-4 mb-5">
    <h2><?= $book->title ?></h2>
</div>
<div class="col-4">
    <img src="<?= $book->img_path ?>" class="w-100">
</div>
<div class="col-8">
    <p>
        <b>isbn:</b> <?= $book->isbn?>
    </p>
    <p>
        <b>Количество страниц:</b> <?= $book->pageCount?>
    </p>
    <p>
        <b>Краткое описание:</b> <?= $book->shortDescription?>
    </p>
    <p>
        <b>Описание:</b> <?= $book->longDescription?>
    </p>
</div>