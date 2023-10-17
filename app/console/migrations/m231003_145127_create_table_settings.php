<?php

use yii\db\Migration;

/**
 * Class m231003_145127_create_table_settings
 */
class m231003_145127_create_table_settings extends Migration
{
    private string $table = '{{%settings}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),

            'key' => $this->string()->unique(),
            'value' => $this->string(),
            'title' => $this->string(),
            'type' => $this->string()
        ]);

        $this->insert($this->table, [
            'key' => 'page_count',
            'value' => 20,
            'title' => 'Количество книг на одной странице',
            'type' => 'integer',
            'updated_at' => time(),
            'created_at' => time(),
        ]);

        $this->insert($this->table, [
            'key' => 'email',
            'value' => 'bilac40053@finghy.com',
            'title' => 'Email адрес получателя сообщения с формы обратной связи',
            'type' => 'email',
            'updated_at' => time(),
            'created_at' => time(),
        ]);

        $this->insert($this->table, [
            'key' => 'url_parse',
            'value' => 'https://gitlab.grokhotov.ru/hr/yii-test-vacancy/-/raw/master/books.json',
            'title' => 'Источник данных для парсинга',
            'type' => 'url',
            'updated_at' => time(),
            'created_at' => time(),
        ]);

        $this->addPrimaryKey('pk_settings_key', $this->table,'key');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }

}
