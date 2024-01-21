<?php

use yii\db\Migration;

/**
 * Class m240120_194211_drop_table_order_items
 */
class m240120_194211_drop_table_order_items extends Migration
{
    private string $table = '{{%order_items}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable($this->table);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

}
