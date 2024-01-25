<?php

namespace api\models\responses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AuthError",
    title: 'Ошибка авторизации',
    properties: [
        new OA\Property(property: "name", type: "string", example: "Unauthorized"),
        new OA\Property(property: "message", type: "string", example: "Your request was made with invalid credentials."),
        new OA\Property(property: "code", type: "integer", format: "int64", example: 0),
        new OA\Property(property: "status", type: "integer", format: "int64", example: 401),
        new OA\Property(property: "type", type: "string", example: "yii\\web\\UnauthorizedHttpException")
    ]
)]
class AuthErrorResponse
{
}
