<?php

use yii\db\Migration;

/**
 * Class m240102_091530_create_table_orders
 */
class m240102_091530_create_table_orders extends Migration
{
    private string $table = '{{%orders}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'number' => $this->string()->unique(),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger(2)->notNull()->comment('From enum OrderStatus'),
            'total_price' => $this->integer()->notNull()->comment('Общая стоимость, выраженная в копейках'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_orders_user_id_id', $this->table, 'user_id', '{{%user}}', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete($this->table);
    }

}
