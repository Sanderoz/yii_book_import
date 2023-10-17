<?php

use yii\db\Migration;

/**
 * Class m231003_141306_create_table_book_categories
 */
class m231003_141306_create_table_book_categories extends Migration
{
    private string $table = '{{%book_categories}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'image' => $this->integer()->null(),
            'parent' => $this->integer()->defaultValue(0)->comment('Родительская категория'),
            'name' => $this->string()->notNull(),
        ]);
        $this->addForeignKey('fk_book_categories_image', $this->table, 'image', '{{%files}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }

}
