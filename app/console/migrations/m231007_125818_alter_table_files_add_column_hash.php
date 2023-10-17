<?php

use yii\db\Migration;

/**
 * Class m231007_125818_alter_table_files_add_column_hash
 */
class m231007_125818_alter_table_files_add_column_hash extends Migration
{
    private string $table = '{{%files}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'hash', $this->string()->unique());
        $this->addColumn($this->table, 'full_path', $this->string());
        $this->createIndex('files_hash', $this->table, 'hash', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('files_hash', $this->table);
        $this->dropColumn($this->table, 'hash');
        $this->dropColumn($this->table, 'full_path');
    }

}
