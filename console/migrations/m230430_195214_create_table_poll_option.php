<?php

use yii\db\Migration;

class m230430_195214_create_table_poll_option extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%poll_option}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'poll_id' => $this->integer()->unsigned()->notNull()->Comment('Poll'),
            'user_id' => $this->integer()->unsigned()->notNull()->Comment('User'),
            'title' => $this->string()->notNull()->Comment('Title'),
            'status' => $this->tinyInteger(4)->notNull()->Comment('Status'),
            'rating' => $this->integer()->notNull()->defaultValue('0')->Comment('Rating'),
            'date_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->Comment('Date add'),
            'date_update' => $this->timestamp()->Comment('Date update'),
            'created_by' => $this->integer()->unsigned()->Comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->Comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->Comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->Comment('Updated at:'),

        ], $tableOptions);

        $this->createIndex('user_id', '{{%poll_option}}', 'user_id');
        $this->createIndex('poll_id', '{{%poll_option}}', 'poll_id');
        $this->addForeignKey('poll_option_ibfk_1', '{{%poll_option}}', 'poll_id', '{{%poll}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('poll_option_ibfk_2', '{{%poll_option}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%poll_option}}');
    }
}
