<?php

use yii\db\Migration;

class m230430_195256_create_table_option_guest_vote extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%option_guest_vote}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'option_id' => $this->integer()->unsigned()->notNull()->Comment('Option'),
            'user_ip' => $this->bigInteger(11)->unsigned()->comment('User IP'),
            'ip_of_user' => $this->string(67)->comment('User IP'),
            'date_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->Comment('Date add'),
            'created_by' => $this->integer()->unsigned()->Comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->Comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->Comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->Comment('Updated at:'),

        ], $tableOptions);

        $this->createIndex('option_id', '{{%option_guest_vote}}', 'option_id');
        $this->addForeignKey('fk_option_guest_vote_poll_option1', '{{%option_guest_vote}}', 'option_id', '{{%poll_option}}', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('{{%option_guest_vote}}');
    }
}
