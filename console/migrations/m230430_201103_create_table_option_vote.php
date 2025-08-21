<?php

use yii\db\Migration;

class m230430_201103_create_table_option_vote extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%option_vote}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'option_id' => $this->integer()->unsigned()->notNull()->Comment('Option'),
            'user_id' => $this->integer()->unsigned()->comment('User'),
            'user_ip' => $this->bigInteger()->unsigned()->comment('User IP'),
            'date_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->Comment('Date add'),
            'created_by' => $this->integer()->unsigned()->Comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->Comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->Comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->Comment('Updated at:'),
        ], $tableOptions);

        $this->createIndex('option_id', '{{%option_vote}}', 'option_id');
        $this->createIndex('user_id', '{{%option_vote}}', 'user_id');
        $this->addForeignKey('option_vote_ibfk_1', '{{%option_vote}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('option_vote_ibfk_2', '{{%option_vote}}', 'option_id', '{{%poll_option}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%option_vote}}');
    }
}
