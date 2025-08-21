<?php

use yii\db\Migration;

class m230430_195219_create_table_poll_comment extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%poll_comment}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'parent_id' => $this->integer()->unsigned()->Comment('Parent'),
            'poll_id' => $this->integer()->unsigned()->notNull()->Comment('Poll'),
            'user_id' => $this->integer()->unsigned()->notNull()->Comment('User'),
            'content' => $this->string()->notNull()->Comment('Content'),
            'status' => $this->tinyInteger(4)->notNull()->Comment('Status'),
            'is_new' => $this->tinyInteger(1)->notNull()->defaultValue('1')->Comment('Is new'),
            'has_new' => $this->tinyInteger(1)->notNull()->defaultValue('1')->Comment('Has_new'),
            'read_by_parent' => $this->tinyInteger(1)->notNull()->defaultValue('0')->Comment('Read by parent'),
            'rating' => $this->integer()->notNull()->defaultValue('0')->Comment('Rating'),
            'date_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->Comment('Date add'),
            'date_update' => $this->timestamp()->Comment('Date update'),
            'created_by' => $this->integer()->unsigned()->Comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->Comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->Comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->Comment('Updated at:'),
        ], $tableOptions);

        $this->createIndex('poll_id', '{{%poll_comment}}', 'poll_id');
        $this->createIndex('user_id', '{{%poll_comment}}', 'user_id');
        $this->createIndex('parent_id', '{{%poll_comment}}', 'parent_id');
        $this->addForeignKey('poll_comment_ibfk_1', '{{%poll_comment}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('poll_comment_ibfk_2', '{{%poll_comment}}', 'poll_id', '{{%poll}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('poll_comment_ibfk_3', '{{%poll_comment}}', 'parent_id', '{{%poll_comment}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%poll_comment}}');
    }
}
