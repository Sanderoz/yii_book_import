<?php

use common\components\enums\PaymentStatus;
use yii\db\Migration;

/**
 * Class m240102_091628_create_table_order_payment
 */
class m240102_091628_create_table_order_payment extends Migration
{
    private string $table = '{{%order_payment}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table,[
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull()->comment('Сумма платежа'),
            'type' => $this->smallInteger(2)->notNull()->comment('1-card/2-sbp (enum - PaymentType)'),
            'status' => $this->smallInteger(2)->notNull()->defaultValue(PaymentStatus::NEW->value)->comment('From enum - PaymentStatus'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240102_091628_create_table_order_payment cannot be reverted.\n";

        return false;
    }

}
