<?php

use yii\db\Migration;

/**
 * Class m231009_143742_alter_table_books_add_column_image
 */
class m231009_143742_alter_table_books_add_column_image extends Migration
{
    private string $table = '{{%books}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'image', $this->integer());
        $this->addForeignKey('fk_books_image', $this->table, 'image', 'files', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_books_image', $this->table);
        $this->dropColumn($this->table, 'image');
    }


}
