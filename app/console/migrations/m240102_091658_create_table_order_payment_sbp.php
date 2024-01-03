<?php

use yii\db\Migration;

/**
 * Class m240102_091658_create_table_order_payment_sbp
 */
class m240102_091658_create_table_order_payment_sbp extends Migration
{
    private string $table = '{{%order_payment_sbp}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'payment_id' => $this->integer()->notNull(),
            'bank' => $this->string()->notNull()->comment('Банк, проводивший операцию'),
            'qr_id' => $this->string()->null()->comment('Идентификатор QR-кода'),
            'order_id' => $this->string()->null()->comment('Номер заказа в платежной системе. Уникален в пределах системы.'),
            'form_url' => $this->string()->null()->comment('URL платежной формы, на который надо перенаправить браузер клиента'),
            'payload' => $this->string()->null()->comment('Содержимое зарегистрированного в СБП QRкода'),
            'qr_status' => $this->string()->null()->comment('Состояние запроса QR_кода'),
            'error_message' => $this->string()->null()->comment('Описание ошибки'),
            'error_code' => $this->string()->null()->comment('Код ошибки'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('fk_order_payment_sbp_payment_id', $this->table, 'payment_id', '{{%order_payment}}', 'id');
        $this->createIndex('index_order_payment_sbp_payment_id', $this->table, 'payment_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete($this->table);
    }

}
