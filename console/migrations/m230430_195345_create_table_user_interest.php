<?php

use yii\db\Migration;

class m230430_195345_create_table_user_interest extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_interest}}', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->notNull()->Comment('User'),
            'activity' => $this->string()->Comment('Activity'),
            'interests' => $this->string()->Comment('Interests'),
            'music' => $this->string()->Comment('Music'),
            'films' => $this->string()->Comment('Films'),
            'shows' => $this->string()->Comment('Shows'),
            'books' => $this->string()->Comment('Books'),
            'games' => $this->string()->Comment('Games'),
            'date_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->Comment('Date add'),
            'date_update' => $this->dateTime()->notNull()->Comment('Date update'),
            'created_by' => $this->integer()->unsigned()->Comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->Comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->Comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->Comment('Updated at:'),
        ], $tableOptions);

        $this->createIndex('user_id', '{{%user_interest}}', 'user_id', true);
        $this->addForeignKey('user_interests_ibfk_1', '{{%user_interest}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%user_interest}}');
    }
}
