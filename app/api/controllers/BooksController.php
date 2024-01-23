<?php

namespace api\controllers;

use api\models\Books;
use api\models\responses\BooksIndexResponse;
use api\models\responses\BooksViewResponse;
use Yii;
use yii\base\InlineAction;
use yii\caching\DbDependency;
use yii\data\ActiveDataProvider;
use yii\filters\HttpCache;
use yii\filters\PageCache;
use yii\rest\ActiveController;
use yii\rest\IndexAction;
use yii\web\NotFoundHttpException;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'books',
    description: 'Получение информации о книгах'
)]
class BooksController extends ActiveController
{
    public $modelClass = Books::class;

    public function behaviors(): array
    {
        return [
            [
                'class' => PageCache::class,
                'only' => ['index'],
                'duration' => 60
            ],
            [
                'class' => PageCache::class,
                'only' => ['view'],
                'duration' => 60,
                'variations' => [
                    Yii::$app->request->get('isbn')
                ],
            ],
        ];
    }

    #[OA\Get(
        path: '/books',
        summary: 'Книги в сокращенном виде',
        tags: ['books'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Получение книг',
                content: new OA\JsonContent(ref: BooksIndexResponse::class)
            )
        ]
    )]
    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => $this->modelClass,
                'prepareDataProvider' => [$this, 'indexPrepareDataProvider']
            ]
        ];
    }

    protected function verbs(): array
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
        ];
    }

    public function indexPrepareDataProvider(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Books::find()->published(),
            'pagination' => [
                'pageSize' => Yii::$app->request->get('per-page', 10),
            ],
        ]);
    }

    #[OA\Get(
        path: '/books/{isbn}',
        summary: 'Детальный просмотр книги',
        tags: ['books'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Вывод книги',
                content: new OA\JsonContent(ref: BooksViewResponse::class)
            ),
            new OA\Response(
                response: 404,
                description: 'Книга не найдена',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'Not Found'),
                        new OA\Property(property: 'message', type: 'string', example: 'Книга не найдена.'),
                        new OA\Property(property: 'status', type: 'integer', format: "int64", example: 404),
                    ]
                )
            )
        ]
    )]
    /**
     * @throws NotFoundHttpException
     */
    public function actionView(string $isbn): Books
    {
        if ($model = Books::findOne(['isbn' => $isbn]))
            return $model;

        throw new NotFoundHttpException('Книга не найдена.');
    }
}
