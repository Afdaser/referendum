<?php

use yii\db\Migration;

/**
 * Створює таблицю для FAQ на сторінках тегів.
 */
class m20241005_120000_create_table_tag_static_faq extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tag_static_faq}}', [
            'id' => $this->primaryKey(),
            'language_id' => $this->integer()->notNull(),
            'question' => $this->text()->notNull(),
            'answer' => $this->text()->notNull(),
            'position' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ]);

        $this->createIndex(
            'idx-tag_static_faq-language_id-position',
            '{{%tag_static_faq}}',
            ['language_id', 'position']
        );

        $this->addForeignKey(
            'fk-tag_static_faq-language_id',
            '{{%tag_static_faq}}',
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
        $this->dropForeignKey('fk-tag_static_faq-language_id', '{{%tag_static_faq}}');
        $this->dropTable('{{%tag_static_faq}}');
    }
}
