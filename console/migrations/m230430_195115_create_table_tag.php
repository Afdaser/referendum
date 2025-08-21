<?php

use yii\db\Migration;

class m230430_195115_create_table_tag extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%tag}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'name' => $this->string()->notNull()->Comment('Name'),
            'language_id' => $this->integer()->unsigned()->Comment('Language'),
            'description' => $this->text()->Comment('Description'),
            'created_by' => $this->integer()->unsigned()->Comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->Comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->Comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->Comment('Updated at:'),
        ], $tableOptions);

        $this->createIndex('fk_tag_language1_idx', '{{%tag}}', 'language_id');
        $this->addForeignKey('fk_tag_language1', '{{%tag}}', 'language_id', '{{%language}}', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('{{%tag}}');
    }
}
