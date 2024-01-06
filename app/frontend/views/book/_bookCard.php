<?php
/**
 * @var Books $book
 * @var array $cartItems
 */

use common\models\Books;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

?>
<div class="col-12 mt-4 mb-5">
    <h2><?= $book->title ?></h2>
</div>
<div class="col-4">
    <img src="<?= $book->img_path ?>" class="w-100">
</div>
<div class="col-8">
    <p>
        <b>isbn:</b> <?= $book->isbn ?>
    </p>
    <p>
        <b>Количество страниц:</b> <?= $book->pageCount ?>
    </p>
    <p>
        <b>Краткое описание:</b> <?= $book->shortDescription ?>
    </p>
    <p>
        <b>Описание:</b> <?= $book->longDescription ?>
    </p>
    <p>
        <b>Стоимость:</b> <?= Yii::$app->formatter->asDecimal($book->price / 100, 2) ?> &#8381;
        <?php
        if (!Yii::$app->user->isGuest)
            if (in_array($book->isbn, $cartItems))
                echo Html::a('В корзине', Url::to('/cart'), ['class' => 'ms-5 btn btn-primary']);
//                echo Html::button('В корзине', [
//                    'id' => 'addInCart',
//                    'class' => 'ms-5 btn btn-primary',
//                    'onclick' => new JsExpression('
//                        $("#addInCart").on("click", function() {
//                            window.location.href = "' . Yii::$app->urlManager->createUrl(['/cart']) . '";
//                        });
//                    ')
//                ]);
            else
                echo Html::a('Добавить в корзину', Url::to(['/cart/add', 'isbn' => $book->isbn]), ['class' => 'ms-5 btn btn-primary']);
        //                echo Html::button('Добавить в корзину', [
        //                    'id' => 'addInCart',
        //                    'class' => 'ms-5 btn btn-primary',
        //                    'onclick' => new JsExpression('
        //                $.ajax({
        //                    url: "' . Yii::$app->urlManager->createUrl(['cart/in-cart', 'isbn' => $book->isbn]) . '",
        //                    type: "GET",
        //                    success: function(response) {
        //                    console.log(response);
        //                        if(response.status == "success"){
        //                            $("#addInCart").text("В корзине (" + response.count + ")");
        //                            $("#addInCart").on(\"click\", function() {
        //                                window.location.href = "' . Yii::$app->urlManager->createUrl(['/cart']) . '";
        //                            });
        //                        }else{
        //                            alert("Не удалось добавить в корзину");
        //                        }
        //                    }
        //                });
        //    '),
        //                ]);
        ?>

    </p>
</div>