<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;


/**
 * @var yii\web\View $this
 * @var ActiveDataProvider $dataProvider
 * */

$this->title = 'Сообщения с формы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'user',
                'value' => $model->userModel->username ?? '-'
            ],
            'message',
            'phone',
            'email',
            [
                'attribute' => 'created_at',
                'format' => ['datetime', 'php:d-m-Y H:i:s']
            ],
        ],
    ]);
    ?>
</div>
