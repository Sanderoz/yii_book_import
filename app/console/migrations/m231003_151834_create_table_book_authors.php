<?php

use yii\db\Migration;

/**
 * Class m231003_151834_create_table_book_authors
 */
class m231003_151834_create_table_book_authors extends Migration
{
    private string $table = '{{%book_authors}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'book' => $this->string()->comment('ISBN книги'),
            'author' => $this->integer()->comment('Id автора')
        ]);
        $this->addForeignKey('fk_book_authors_book', $this->table, 'book', '{{%books}}', 'isbn', 'CASCADE');
        $this->addForeignKey('fk_book_authors_author', $this->table, 'author', '{{%authors}}', 'id', 'CASCADE');
        $this->addPrimaryKey('pk_book_authors_book_author', $this->table, ['book', 'author']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropTable($this->table);
    }

}
