<?php

use yii\db\Migration;

/**
 * Class m231003_145138_create_table_messages
 */
class m231003_145138_create_table_messages extends Migration
{
    private string $table = '{{%messages}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'user' => $this->integer()->null(),
            'created_at' => $this->integer()->notNull(),

            'phone' => $this->string(20)->notNull(),
            'email' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'message' => $this->text()->notNull(),
        ]);

        $this->addForeignKey('fk_messages_user', $this->table, 'user', '{{%user}}', 'id');

        $this->insert($this->table, [
            'phone' => '79452154857',
            'email' => 'tempmail@mail.ru',
            'name' => 'Сергей',
            'message' => 'Книгу бы',
            'created_at' => time()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }

}
