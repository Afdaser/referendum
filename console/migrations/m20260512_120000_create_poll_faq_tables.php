<?php

use yii\db\Migration;

class m20260512_120000_create_poll_faq_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%poll_static_faq}}', [
            'id' => $this->primaryKey(),
            'language_id' => $this->integer()->notNull(),
            'question' => $this->text()->notNull(),
            'answer' => $this->text()->notNull(),
            'position' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ]);
        $this->createIndex('idx-poll_static_faq-language_id-position', '{{%poll_static_faq}}', ['language_id', 'position']);
        $this->addForeignKey('fk-poll_static_faq-language_id', '{{%poll_static_faq}}', 'language_id', '{{%language}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%poll_faq}}', [
            'id' => $this->primaryKey(),
            'poll_id' => $this->integer()->notNull(),
            'question' => $this->text()->notNull(),
            'answer' => $this->text()->notNull(),
            'position' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ]);
        $this->createIndex('idx-poll_faq-poll_id-position', '{{%poll_faq}}', ['poll_id', 'position']);
        $this->addForeignKey('fk-poll_faq-poll_id', '{{%poll_faq}}', 'poll_id', '{{%poll}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-poll_faq-poll_id', '{{%poll_faq}}');
        $this->dropTable('{{%poll_faq}}');

        $this->dropForeignKey('fk-poll_static_faq-language_id', '{{%poll_static_faq}}');
        $this->dropTable('{{%poll_static_faq}}');
    }
}
