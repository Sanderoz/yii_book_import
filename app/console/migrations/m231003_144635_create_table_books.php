<?php

use yii\db\Migration;

/**
 * Class m231003_144635_create_table_books
 */
class m231003_144635_create_table_books extends Migration
{
    private string $table = '{{%books}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'pageCount' => $this->integer()->notNull()->comment('Количество страниц'),
            'status' => $this->string(30)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'publishedDate' => $this->date()->comment('Дата публикации'),

            'isbn' => $this->string()->unique(),
            'title' => $this->string()->notNull()->comment('Заголовок'),
            'shortDescription' => $this->text(),
            'longDescription' => $this->text(),
        ]);

        $this->addPrimaryKey('pk_books_isbn', $this->table, 'isbn');
        $this->createIndex('books_index_title', $this->table, 'title');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }

}
