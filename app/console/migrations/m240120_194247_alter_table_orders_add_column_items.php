<?php

use yii\db\Migration;

/**
 * Class m240120_194247_alter_table_orders_add_column_items
 */
class m240120_194247_alter_table_orders_add_column_items extends Migration
{
    private string $table = '{{%orders}}';

    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->addColumn(
            $this->table,
            'items',
            $this->json()->comment('Элементы корзины')->notNull()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

}
