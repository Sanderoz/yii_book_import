<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    private $table = '{{%user}}';

    /**
     * @throws \yii\base\Exception
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull()->defaultValue(20)->comment('0 - удален, 10 - отключен, 20 - активен'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'is_admin' => $this->boolean()->defaultValue(false),
            'verified_at' => $this->integer()->comment('Дата подверждения аккаунта'),

            'phone' => $this->string(20)->null(),
            'auth_key' => $this->string(32)->notNull(),
            'username' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
        ], $tableOptions);

        $this->insert($this->table, [
            'status' => 20,
            'created_at' => time(),
            'updated_at' => time(),
            'is_admin' => true,
            'verified_at' => time(),

            'phone' => '79666666666',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'username' => 'admin',
            'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
            'email' => 'adminmail@mail.ru',
        ]);

        $this->insert($this->table, [
            'status' => 20,
            'created_at' => time(),
            'updated_at' => time(),
            'is_admin' => false,
            'verified_at' => time(),

            'phone' => '79777777777',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'username' => 'user',
            'password_hash' => Yii::$app->security->generatePasswordHash('user'),
            'email' => 'usermail@mail.ru',
        ]);
    }

    public function down()
    {
        $this->dropTable($this->table);
    }
}
