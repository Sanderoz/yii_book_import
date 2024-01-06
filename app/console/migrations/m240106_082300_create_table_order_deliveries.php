<?php

use yii\db\Migration;

/**
 * Class m240106_082300_create_table_order_deliveries
 */
class m240106_082300_create_table_order_deliveries extends Migration
{
    private string $table = '{{%order_deliveries}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'type' => $this->integer()->notNull()->comment('enum DeliveryType'),
            'address' => $this->string()->null()->comment('Адрес доставки'),
            'cost' => $this->integer()->unsigned()->check('cost=0 or cost>=100'),
            'delivery_date' => $this->date()->null(),
            'status' => $this->integer()->notNull()->comment('enum DeliveryStatus'),
            'updated_at' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('fk_order_deliveries_order_id', $this->table, 'order_id', '{{%orders}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_order_deliveries_order_id', $this->table);
        $this->delete($this->table);
    }

}
