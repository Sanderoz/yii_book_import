<?php

namespace api\models;

use Yii;
use yii\db\ActiveRecord;
use OpenApi\Attributes as OA;

/**
 * @property int $pageCount Количество страниц
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $image
 * @property string $publishedDate Дата публикации
 * @property string $isbn
 * @property string $title Заголовок
 * @property string $shortDescription
 * @property string|null $longDescription
 * @property int $price
 */
class Books extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%books}}';
    }

    public static function find(): BooksQuery
    {
        return new BooksQuery(get_called_class());
    }

    #[OA\Schema(
        schema: "BookIndex",
        required: ["title", "shortDescription", "publishedDate", "isbn", "price"],
        properties: [
            new OA\Property(property: "price", type: "integer", format: "int64", example: 50000),
            new OA\Property(property: "title", type: "string", example: "Personal Videoconferencing"),
            new OA\Property(property: "shortDescription", type: "string", example: "\"Personal Videoconferencing is having an enormous impact on business. Evan Rosen has quantified that impact with examples of real world implementations and provided a primer on how businesses can achieve this competitive advantage for themselves.\"  --Frank Gill, Executive Vice President, Internet and Communications Group, Intel    \"The book is very good: it is clear and the examples of user applications are excellent\"  --Ralph Ungermann, CEO, First Virtual Corporation "),
            new OA\Property(property: "publishedDate", type: "string", format: "date-time", example: "1996-06-01"),
            new OA\Property(property: "isbn", type: "string", example: "013268327X"),
        ]
    )]
    #[OA\Schema(
        schema: "BookView",
        required: ["title", "shortDescription", "longDescription", "pageCount", "publishedDate", "isbn", "price"],
        properties: [
            new OA\Property(property: "price", type: "integer", format: "int64", example: 50000),
            new OA\Property(property: "pageCount", type: "integer", format: "int64", example: 420),
            new OA\Property(property: "title", type: "string", example: "Personal Videoconferencing"),
            new OA\Property(property: "shortDescription", type: "string", example: "\"Personal Videoconferencing is having an enormous impact on business. Evan Rosen has quantified that impact with examples of real world implementations and provided a primer on how businesses can achieve this competitive advantage for themselves.\"  --Frank Gill, Executive Vice President, Internet and Communications Group, Intel    \"The book is very good: it is clear and the examples of user applications are excellent\"  --Ralph Ungermann, CEO, First Virtual Corporation "),
            new OA\Property(property: "longDescription", type: "string", example: "The first book on the most powerful communication tool since the development of the personal computer, Personal Videoconferencing will help you streamline your business and gain a competitive edge. It summarizes the experience of more than seventy companies in many industries in the use of desktop and laptop videoconferencing to collaborate on documents and applications while communicating through video, face-to-face. Anyone who shares information with others will benefit from reading this book.  "),
            new OA\Property(property: "publishedDate", type: "string", format: "date-time", example: "1996-06-01"),
            new OA\Property(property: "isbn", type: "string", example: "013268327X"),
        ]
    )]
    public function fields(): array
    {
        return match (Yii::$app->controller->action->id) {
            'index' => [
//                    'service_id' => fn(ApiService $model) => $model->id,
                'title',
                'shortDescription',
                'publishedDate',
                'isbn',
                'price'
            ],
            'view' => [
                'title',
                'shortDescription',
                'longDescription',
                'pageCount',
                'publishedDate',
                'isbn',
                'price'
            ],
            default => parent::fields(),
        };
    }
}