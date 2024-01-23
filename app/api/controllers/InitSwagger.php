<?php

namespace api\controllers;

use OpenApi\Attributes as OA;
use OpenApi\Attributes\OpenApi;

#[OpenApi(
    openapi: '3.1.0',
    info: new OA\Info(
        version: "1.0.0",
        description: 'Small bookstore',
        title: 'Book shop'
    ),
    security: [
        new OA\SecurityScheme(
            securityScheme: 'bearerAuth',
            type: 'http',
            bearerFormat: 'JWT',
            scheme: 'bearer',
        ),
    ],
    components: new OA\Components(
        securitySchemes: [
            new OA\SecurityScheme(
                securityScheme: 'bearerAuth',
                type: 'http',
                description: 'Bearer token',
                name: 'Authorization',
                in: 'header',
                bearerFormat: 'JWT',
                scheme: 'bearer',
            )
        ]
    ))
]
class InitSwagger
{

}