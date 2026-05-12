<?php

use yii\db\Migration;

/**
 * Створює таблицю FAQ для окремих сторінок опитувань.
 */
class m20260512_120000_create_table_poll_static_faq extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%poll_static_faq}}', [
            'id' => $this->primaryKey(),
            'poll_id' => $this->integer()->notNull(),
            'question' => $this->text()->notNull(),
            'answer' => $this->text()->notNull(),
            // Якщо 1 — дозволяємо підстановку токенів (@title, @votes_total, тощо) перед рендером.
            'is_dynamic' => $this->boolean()->notNull()->defaultValue(0),
            'position' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ]);

        $this->createIndex('idx-poll_static_faq-poll_id-position', '{{%poll_static_faq}}', ['poll_id', 'position']);
        $this->addForeignKey(
            'fk-poll_static_faq-poll_id',
            '{{%poll_static_faq}}',
            'poll_id',
            '{{%poll}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-poll_static_faq-poll_id', '{{%poll_static_faq}}');
        $this->dropTable('{{%poll_static_faq}}');
    }
}
