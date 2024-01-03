<?php

use yii\db\Migration;

/**
 * Class m231003_144634_create_table_authors
 */
class m231003_144634_create_table_authors extends Migration
{
    private string $table = '{{%authors}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
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
