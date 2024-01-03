<?php

use yii\db\Migration;

/**
 * Class m231003_141305_create_table_files
 */
class m231003_141305_create_table_files extends Migration
{
    private string $table = '{{%files}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'name' => $this->string()->null(),
            'original_name' => $this->string(),
            'path' => $this->string()->notNull()->comment('Ссылка на внутреннее хранилище'),
            's3path' => $this->string()->comment('Ссылка на хранилище s3')
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
