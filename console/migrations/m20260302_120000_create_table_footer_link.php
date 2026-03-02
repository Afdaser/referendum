<?php

use yii\db\Migration;

/**
 * Створює таблицю для посилань футера, які керуються з адмінки.
 */
class m20260302_120000_create_table_footer_link extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%footer_link}}', [
            'id' => $this->primaryKey(),
            // Код мови (наприклад: uk, en-US). Порожнє значення = для всіх мов.
            'language_code' => $this->string(32)->null(),
            // Анкор посилання у футері.
            'anchor' => $this->string(255)->notNull(),
            // URL посилання (відносний або абсолютний).
            'url' => $this->string(2048)->notNull(),
            // Порядок сортування в межах мови.
            'sort_order' => $this->integer()->notNull()->defaultValue(100),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ]);

        // Індекс пришвидшує вибірку по мові та сортуванню.
        $this->createIndex('idx_footer_link_language_sort', '{{%footer_link}}', ['language_code', 'sort_order']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_footer_link_language_sort', '{{%footer_link}}');
        $this->dropTable('{{%footer_link}}');
    }
}
