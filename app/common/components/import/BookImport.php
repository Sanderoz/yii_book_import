<?php

namespace common\components\import;

use common\handlers\FileImportHandler;
use common\components\enums\BookStatus;
use common\models\Authors;
use common\models\BookAuthors;
use common\models\BookBelongsCategories;
use common\models\BookCategories;
use common\models\Books;
use Yii;
use yii\db\Exception;

class BookImport
{
    private int $success = 0;
    private array $errors = [
        'count' => 0,
        'isbn' => []
    ];

    public function __construct(
        public array  $books,
        private Books $model = new Books()
    )
    {
    }

    /**
     * @param callable $callback
     * @return array[successful, unsuccessful, unsuccessful_isbn]
     * @throws \Exception
     */
    public function import(callable $callback): array
    {
        foreach ($this->books as $index => $book) {
            $callback($index + 1);
            if (empty($book['isbn'])) {
                $this->addError('index: ' . $index);
                continue;
            }

            $this->setValues($book);
            if ($this->model->validate(['pageCount', 'title', 'shortDescription', 'longDescription']) and $this->saveModel()) {
                if (isset($book['thumbnailUrl']))
                    $this->addImportFileInQueue($book['thumbnailUrl']);

                $this->addSuccess();
                $this->saveCategoryRelations($book['categories'] ?? []);
                $this->saveAuthorRelations($book['authors'] ?? []);
                continue;
            }
            $this->addError($this->model->isbn);
        }

        return [
            'successful' => $this->getSuccessCount(),
            'unsuccessful' => $this->getErrorsCount(),
            'unsuccessful_isbn' => $this->getErrorsEsbn()
        ];
    }

    /**
     * @param array $fields
     */
    private function setValues(array $fields): void
    {
        $this->model->setAttributes($fields);
        $this->model->status = BookStatus::getStatus($fields['status']);
        $this->model->created_at = time();
        $this->model->updated_at = time();
        $this->model->publishedDate = empty($fields['publishedDate']) ? null : self::getPublishedDate(array_values($fields['publishedDate'])[0] ?? null);
    }

    /**
     * @throws Exception
     */
    private function saveModel(): bool
    {
        $params = [];

        return Yii::$app->db->createCommand()->upsert(
                Books::tableName(),
                $this->model->getAttributes(),
                $this->model->getAttributes([
                    'pageCount',
                    'status',
                    'updated_at',
                    'publishedDate',
                    'title',
                    'shortDescription',
                    'longDescription',
                    'image'
                ]),
                $params
            )->execute() > 0;
    }

    /**
     * @param string|null $date_string
     * @return string|null
     */
    private static function getPublishedDate(?string $date_string): ?string
    {
        $publishedDate = null;
        if (!empty($date_string)) {
            if ($date = \DateTime::createFromFormat('Y-m-d\TH:i:s.uO', $date_string))
                $publishedDate = $date->format('Y-m-d');
        }
        return $publishedDate;
    }

    /**
     * @param array $categories
     * @return void
     * @throws \Exception
     */
    private function saveCategoryRelations(array $categories): void
    {
        foreach ($categories as $category) {
            if (empty($category))
                continue;

            $categoryModel = new BookBelongsCategories();
            $categoryModel->book = $this->model->isbn;
            $categoryModel->category = BookCategories::getCategory($category);
            if ($categoryModel->validate())
                $categoryModel->save();
        }
    }

    /**
     * @param array $authors
     * @return void
     * @throws \Exception
     */
    private function saveAuthorRelations(array $authors): void
    {
        foreach ($authors as $author) {
            if (empty($author))
                continue;

            $categoryModel = new BookAuthors();
            $categoryModel->book = $this->model->isbn;
            $categoryModel->author = Authors::getAuthor($author);
            if ($categoryModel->validate())
                $categoryModel->save();
        }
    }

    /**
     * @param string $url
     * @param string $isbn
     * @return void
     */
    private function addImportFileInQueue(string $url): void
    {
        Yii::$app->queue->push(new FileImportHandler([
            'url' => $url,
            'book' => $this->model->isbn,
        ]));
    }

    /**
     * @return int
     */
    private function getSuccessCount(): int
    {
        return $this->success;
    }

    /**
     * @return int
     */
    private function getErrorsCount(): int
    {
        return $this->errors['count'];
    }

    /**
     * @return string
     */
    private function getErrorsEsbn(): string
    {
        return implode(', ', $this->errors['isbn']);
    }

    /**
     * @return void
     */
    private function addSuccess(): void
    {
        $this->success++;
    }

    /**
     * @param string|null $isbn
     * @return void
     */
    private function addError(?string $isbn): void
    {
        $this->errors['count']++;
        $this->errors['isbn'][] = $isbn;
    }

}