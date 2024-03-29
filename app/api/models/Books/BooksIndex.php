<?php

namespace api\models\Books;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "BookIndex",
    title: 'Сокращенный вывод книг',
    type: 'array',
    items: new OA\Items(
        properties: [
            new OA\Property(property: "price", type: "integer", format: "int64", example: 50000),
            new OA\Property(property: "title", type: "string", example: "Personal Videoconferencing"),
            new OA\Property(property: "shortDescription", type: "string", example: "\"Personal Videoconferencing is having an enormous impact on business. Evan Rosen has quantified that impact with examples of real world implementations and provided a primer on how businesses can achieve this competitive advantage for themselves.\"  --Frank Gill, Executive Vice President, Internet and Communications Group, Intel    \"The book is very good: it is clear and the examples of user applications are excellent\"  --Ralph Ungermann, CEO, First Virtual Corporation "),
            new OA\Property(property: "publishedDate", type: "string", format: "date-time", example: "1996-06-01"),
            new OA\Property(property: "isbn", type: "string", example: "013268327X")
        ]
    )
)]
class BooksIndex extends Books
{
    public function fields(): array
    {
        return [
            'title',
            'shortDescription',
            'publishedDate',
            'isbn',
            'price'
        ];
    }
}
