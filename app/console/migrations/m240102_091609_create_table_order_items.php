<?php

use yii\db\Migration;

/**
 * Class m240102_091609_create_table_order_items
 */
class m240102_091609_create_table_order_items extends Migration
{
    private string $table = '{{%order_items}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'book_isbn' => $this->string(),
            'order_id' => $this->integer(),
            'price' => $this->integer()->comment('Стоимость товара на момент оформления заказа'),
            'count' => $this->integer()->comment('Количество заказанных книг')
        ]);

        $this->addForeignKey('fk_order_items_book_isbn', $this->table, 'book_isbn', '{{%books}}', 'isbn');
        $this->addForeignKey('fk_order_items_order_id', $this->table, 'order_id', '{{%orders}}', 'id');
        $this->addPrimaryKey('pk_order_items_book_isbn_order_id', $this->table, ['book_isbn', 'order_id']);
        $this->createIndex('index_order_items_book_isbn_order_id', $this->table, ['book_isbn', 'order_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete($this->table);
    }

}
