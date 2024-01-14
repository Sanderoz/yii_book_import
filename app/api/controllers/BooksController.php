<?php

namespace api\controllers;

use api\models\Books;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\rest\IndexAction;
use yii\web\NotFoundHttpException;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    description: 'Small bookstore',
    title: 'Book shop'
)]
#[OA\Tag(
    name: 'books',
    description: 'Operations about books'
)]
class BooksController extends ActiveController
{
    public $modelClass = Books::class;

    #[OA\Get(
        path: '/books',
        summary: 'List all books',
        tags: ['books'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "price", type: "integer", format: "int64", example: 50000),
                                    new OA\Property(property: "title", type: "string", example: "Personal Videoconferencing"),
                                    new OA\Property(property: "shortDescription", type: "string", example: "\"Personal Videoconferencing is having an enormous impact on business. Evan Rosen has quantified that impact with examples of real world implementations and provided a primer on how businesses can achieve this competitive advantage for themselves.\"  --Frank Gill, Executive Vice President, Internet and Communications Group, Intel    \"The book is very good: it is clear and the examples of user applications are excellent\"  --Ralph Ungermann, CEO, First Virtual Corporation "),
                                    new OA\Property(property: "publishedDate", type: "string", format: "date-time", example: "1996-06-01"),
                                    new OA\Property(property: "isbn", type: "string", example: "013268327X"),
                                ]
                            )
                        )
                    ]
                )
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
        summary: 'View book',
        tags: ['books'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "price", type: "integer", format: "int64", example: 50000),
                                    new OA\Property(property: "pageCount", type: "integer", format: "int64", example: 420),
                                    new OA\Property(property: "title", type: "string", example: "Personal Videoconferencing"),
                                    new OA\Property(property: "shortDescription", type: "string", example: "\"Personal Videoconferencing is having an enormous impact on business. Evan Rosen has quantified that impact with examples of real world implementations and provided a primer on how businesses can achieve this competitive advantage for themselves.\"  --Frank Gill, Executive Vice President, Internet and Communications Group, Intel    \"The book is very good: it is clear and the examples of user applications are excellent\"  --Ralph Ungermann, CEO, First Virtual Corporation "),
                                    new OA\Property(property: "longDescription", type: "string", example: "The first book on the most powerful communication tool since the development of the personal computer, Personal Videoconferencing will help you streamline your business and gain a competitive edge. It summarizes the experience of more than seventy companies in many industries in the use of desktop and laptop videoconferencing to collaborate on documents and applications while communicating through video, face-to-face. Anyone who shares information with others will benefit from reading this book.  "),
                                    new OA\Property(property: "publishedDate", type: "string", format: "date-time", example: "1996-06-01"),
                                    new OA\Property(property: "isbn", type: "string", example: "013268327X"),
                                ]
                            )
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Books not found',
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
