<?php

use yii\db\Migration;

class m230430_195315_create_table_poll_comment_rating extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%poll_comment_rating}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'poll_comment_id' => $this->integer()->unsigned()->notNull()->Comment('Poll comment'),
            'user_id' => $this->integer()->unsigned()->notNull()->Comment('User'),
            'rating' => $this->integer(2)->notNull()->Comment('Rrating'),
            'date_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->Comment('Date add'),
            'created_by' => $this->integer()->unsigned()->Comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->Comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->Comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->Comment('Updated at:'),

        ], $tableOptions);

        $this->createIndex('comment_user', '{{%poll_comment_rating}}', ['poll_comment_id', 'user_id'], true);
        $this->createIndex('user_id', '{{%poll_comment_rating}}', 'user_id');
        $this->addForeignKey('poll_comment_ratings_ibfk_1', '{{%poll_comment_rating}}', 'poll_comment_id', '{{%poll_comment}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('poll_comment_ratings_ibfk_2', '{{%poll_comment_rating}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%poll_comment_rating}}');
    }
}
