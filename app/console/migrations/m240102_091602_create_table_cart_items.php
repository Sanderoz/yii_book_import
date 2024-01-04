<?php

use yii\db\Migration;

/**
 * Class m240102_091602_create_table_cart_items
 */
class m240102_091602_create_table_cart_items extends Migration
{
    private string $table = '{{%cart_items}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'user_id' => $this->integer(),
            'book_isbn' => $this->string(),
            'count' => $this->integer()
        ]);

        $this->addForeignKey('fk_cart_items_book_isbn', $this->table, 'book_isbn', '{{%books}}', 'isbn');
        $this->addForeignKey('fk_cart_items_user_id', $this->table, 'user_id', '{{%user}}', 'id');
        $this->addPrimaryKey('pk_cart_items_book_isbn_user_id', $this->table, ['book_isbn', 'user_id']);
        $this->createIndex('index_cart_items_book_isbn_user_id', $this->table, ['book_isbn', 'user_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('index_cart_items_book_isbn_user_id', $this->table);
        $this->delete($this->table);
    }

}
