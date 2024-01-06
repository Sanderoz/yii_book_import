<?php

namespace console\controllers;

use common\components\exceptions\SettingsException;
use common\components\import\BookImport;
use common\components\import\FileImport;
use common\models\Files;
use common\models\Settings;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\FileHelper;

class ImportController extends Controller
{
    /**
     * @throws \Exception
     */
    public function actionIndex($updateImages = false): void
    {
        $updateImages = $updateImages === 'true';
        FileHelper::createDirectory(Files::getFullFilePath('/temp/'));
        $filename = \Yii::getAlias('@commonUploads') . '/temp/book-' . time() . '.json';
        $file = new FileImport(
            Settings::findOne(['url_parse'])->value ?? \Yii::getAlias('@commonUploads') . '/import-books.json',//new SettingsException('Url не задан'),
            $filename
        );

        $books = $file->getJson();

        echo 'Начинаю импорт' . PHP_EOL;
        $count_books = count($books);
        Console::startProgress(0, $count_books);

        $import = new BookImport($books);
        $result = $import->import($updateImages, fn($current) => Console::updateProgress($current, $count_books, "Обработано $current из $count_books записей "));

        Console::endProgress();
        $file->unlinkFile();

        echo 'Импорт закончен' . PHP_EOL;
        echo 'Успешно: ' . $result['successful'] . PHP_EOL;
        echo 'Неудачно: ' . $result['unsuccessful'] . PHP_EOL;
        echo 'Неудачные isbn: ' . $result['unsuccessful_isbn'] . PHP_EOL;
    }
}