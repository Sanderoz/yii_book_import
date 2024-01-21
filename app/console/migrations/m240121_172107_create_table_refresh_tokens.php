<?php

use yii\db\Migration;

/**
 * Class m240121_172107_create_table_refresh_tokens
 */
class m240121_172107_create_table_refresh_tokens extends Migration
{
    private string $table = '{{%refresh_tokens}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'token' => $this->string(32)->unique()->notNull()->comment('refresh token'),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'expired_at' => $this->integer()->notNull(),
            'used' => $this->boolean()->defaultValue(false)->comment('Был ли токен использован')
        ]);

        $this->addPrimaryKey('pk_refresh_tokens_token', $this->table, 'token');
        $this->createIndex('refresh_token_token_index', $this->table, 'token');
        $this->addForeignKey('fk_refresh_token_user_id', $this->table, 'user_id', '{{%user}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('refresh_token_token_index', $this->table);
        $this->dropForeignKey('fk_refresh_token_user_id', $this->table);
        $this->dropTable($this->table);
    }

}
