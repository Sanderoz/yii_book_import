<?php

use yii\db\Migration;

/**
 * Class m231003_144916_create_table_book_belongs_category
 */
class m231003_144916_create_table_book_belongs_category extends Migration
{
    private string $table = '{{%book_belongs_category}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'category' => $this->integer()->comment('ID категории, к которой относится книга'),
            'book' => $this->string()->comment('Isbn книги'),
        ]);
        $this->addForeignKey('fk_book_belongs_category_category', $this->table, 'category', '{{%book_categories}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_book_belongs_category_book', $this->table, 'book', '{{%books}}', 'isbn', 'CASCADE');
        $this->addPrimaryKey('pk_book_belongs_category_category_book_pk', $this->table, ['category', 'book']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }

}
