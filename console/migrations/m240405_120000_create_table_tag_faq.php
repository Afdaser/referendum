<?php

use yii\db\Migration;

/**
 * Створює таблицю для зберігання FAQ по тегах.
 */
class m240405_120000_create_table_tag_faq extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tag_faq}}', [
            'id' => $this->primaryKey(),
            'language_id' => $this->integer()->notNull(),
            'question' => $this->text()->null(),
            'answer' => $this->text()->null(),
            'position' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ]);

        $this->createIndex('idx-tag_faq-language_id-position', '{{%tag_faq}}', ['language_id', 'position']);

        $this->addForeignKey(
            'fk-tag_faq-language_id',
            '{{%tag_faq}}',
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
        $this->dropForeignKey('fk-tag_faq-language_id', '{{%tag_faq}}');
        $this->dropIndex('idx-tag_faq-language_id-position', '{{%tag_faq}}');
        $this->dropTable('{{%tag_faq}}');
    }
}
