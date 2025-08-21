<?php

use yii\db\Migration;

class m230430_195055_create_table_language extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%language}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'name' => $this->string()->notNull()->Comment('Name'),
            'locale' => 'char(10) COLLATE utf8_bin NOT NULL COMMENT "Locale"',
            'title' => $this->string()->notNull()->Comment('Title'),
            'created_by' => $this->integer()->unsigned()->Comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->Comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->Comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->Comment('Updated at:'),
            "UNIQUE (`name`)",
            "UNIQUE (`locale`)",
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%language}}');
    }
}
