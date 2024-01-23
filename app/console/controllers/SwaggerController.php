<?php

namespace console\controllers;

use OpenApi\Generator;
use Yii;
use yii\console\Controller;

class SwaggerController extends Controller
{
    // https://zircote.github.io/
    public function actionGenerate(): string
    {
        $openapi = Generator::scan([Yii::getAlias('@api/')]);
        header('Content-Type: application/x-yaml');

        $filePath = './api.yaml';
        file_put_contents($filePath, $openapi->toYaml());
        return "Swagger documentation saved to: $filePath";
    }
}
