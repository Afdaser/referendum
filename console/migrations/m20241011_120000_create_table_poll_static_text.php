<?php

use yii\db\Migration;

/**
 * Створює таблицю для збереження статичних мета-даних сторінки опитувань.
 */
class m20241011_120000_create_table_poll_static_text extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%poll_static_text}}', [
            'id' => $this->primaryKey(),
            'language_id' => $this->integer()->notNull(),
            'heading' => $this->string(255)->null(),
            'meta_title' => $this->string(255)->null(),
            'meta_description' => $this->text()->null(),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ]);

        $this->createIndex('idx-poll_static_text-language_id', '{{%poll_static_text}}', 'language_id', true);
        $this->addForeignKey(
            'fk-poll_static_text-language_id',
            '{{%poll_static_text}}',
            'language_id',
            '{{%language}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-poll_static_text-language_id', '{{%poll_static_text}}');
        $this->dropIndex('idx-poll_static_text-language_id', '{{%poll_static_text}}');
        $this->dropTable('{{%poll_static_text}}');
    }
}
