<?php

use yii\db\Migration;

/**
 * Class m240103_094741_alter_table_books_add_column_price
 */
class m240103_094741_alter_table_books_add_column_price extends Migration
{
    private string $table = '{{%books}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'price', $this->integer()->defaultValue(0)->notNull()->comment('Стоимость книги'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'price');
    }
}
