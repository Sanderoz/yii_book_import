<?php

use yii\widgets\ListView;

/** @var yii\web\View $this
 * @var \common\models\BookCategories $categories
 * @var \yii\data\ActiveDataProvider $books
 * @var \common\models\BooksSearch $searchModel
 * */

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <div class="row">
        <?php
        echo $this->render('_search', ['searchModel' => $searchModel]);
        if (empty($categories) and empty($books)) {
            echo '<h2>Увы, ничего не найдено</h2>';
        } else {
            if (!empty($categories)) {
                echo '<h2 class="mb-4 mt-4">Категории книг</h2>';
                foreach ($categories as $category) {
                    echo $this->render('_categoryCard', ['category' => $category]);
                }
            }
            if (!empty($books)) {
                echo '<h2 class="mb-4">Книги</h2>';
                echo ListView::widget([
                    'dataProvider' => $books,
                    'itemView' => '_bookCard',
                    'itemOptions' => ['class' => 'col-2 category_card'],
                    'options' => ['class' => 'row'],
                ]);
            }
        }

        ?>
    </div>
</div>
