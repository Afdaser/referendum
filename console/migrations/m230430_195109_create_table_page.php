<?php

use yii\db\Migration;

class m230430_195109_create_table_page extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%page}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'slug' => $this->string(128)->notNull()->Comment('Slug'),
            'language_id' => $this->integer()->unsigned()->notNull()->defaultValue('1')->Comment('Language'),
            'name' => $this->string()->notNull()->Comment('Name'),
            'title' => $this->string()->notNull()->Comment('Title'),
            'content' => $this->text()->Comment('Content'),
            'describe' => $this->text()->Comment('Describe'),
            'date_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->Comment('Date add'),
            'date_update' => $this->timestamp()->Comment('Date update'),
            'created_by' => $this->integer()->unsigned()->Comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->Comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->Comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->Comment('Updated at:'),
        ], $tableOptions);

        $this->createIndex('slug_2_language', '{{%page}}', ['slug', 'language_id'], true);
        $this->createIndex('language_id', '{{%page}}', 'language_id');
        $this->addForeignKey('pages_language_fk', '{{%page}}', 'language_id', '{{%language}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%page}}');
    }
}
