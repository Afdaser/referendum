<?php

use yii\db\Migration;

/**
 * Створює таблицю для статичного тексту на тегах.
 */
class m20240930_120000_create_table_tag_static_text extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tag_static_text}}', [
            'id' => $this->primaryKey(),
            'language_id' => $this->integer()->notNull()->unique(),
            'content' => $this->text()->null(),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ]);

        $this->addForeignKey(
            'fk-tag_static_text-language_id',
            '{{%tag_static_text}}',
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
        $this->dropForeignKey('fk-tag_static_text-language_id', '{{%tag_static_text}}');
        $this->dropTable('{{%tag_static_text}}');
    }
}
