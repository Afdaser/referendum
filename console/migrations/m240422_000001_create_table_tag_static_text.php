<?php

use yii\db\Migration;

class m240422_000001_create_table_tag_static_text extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%tag_static_text}}', [
            'id' => $this->primaryKey()->unsigned()->comment('ID'),
            'tag_id' => $this->integer()->unsigned()->notNull()->comment('Tag'),
            'language_id' => $this->integer()->unsigned()->notNull()->comment('Language'),
            'content' => $this->text()->notNull()->comment('Content'),
            'created_by' => $this->integer()->unsigned()->comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->comment('Updated at:'),
        ], $tableOptions);

        $this->createIndex('idx_tag_static_text_tag_language', '{{%tag_static_text}}', ['tag_id', 'language_id'], true);
        $this->createIndex('idx_tag_static_text_language', '{{%tag_static_text}}', 'language_id');
        $this->addForeignKey(
            'fk_tag_static_text_tag',
            '{{%tag_static_text}}',
            'tag_id',
            '{{%tag}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_tag_static_text_language',
            '{{%tag_static_text}}',
            'language_id',
            '{{%language}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('{{%tag_static_text}}');
    }
}
