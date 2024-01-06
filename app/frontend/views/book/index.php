<?php

/**
 * @var yii\web\View $this
 * @var \common\models\Books $book
 * @var array $cartItems
 */

$this->title = $book->title;
?>
<div class="book-index">
    <div class="row">
        <?php
        if (empty($book))
            echo '<h3>Книга не найдена</h3>';
        else
            echo $this->render('_bookCard', ['book' => $book, 'cartItems' => $cartItems]);
        ?>
    </div>
</div>