<?php

use yii\db\Migration;

class m230430_195303_create_table_user_language extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_language}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'user_id' => $this->integer()->unsigned()->notNull()->Comment('User'),
            'language_id' => $this->integer()->unsigned()->notNull()->Comment('Language'),
        ], $tableOptions);

        $this->createIndex('language_id', '{{%user_language}}', 'language_id');
        $this->createIndex('user_id', '{{%user_language}}', 'user_id');
        $this->addForeignKey('user_languages_ibfk_1', '{{%user_language}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('user_languages_ibfk_2', '{{%user_language}}', 'language_id', '{{%language}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%user_language}}');
    }
}
