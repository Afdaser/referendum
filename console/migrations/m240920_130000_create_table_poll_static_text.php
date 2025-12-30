<?php

use yii\db\Migration;

class m240920_130000_create_table_poll_static_text extends Migration
{
    public function safeUp()
    {
        // Створюємо таблицю для статичного тексту та мета-тегів сторінок опитувань.
        $this->createTable('{{%poll_static_text}}', [
            'id' => $this->primaryKey(),
            'poll_id' => $this->integer()->notNull()->comment('Poll'),
            'content' => $this->text()->null()->comment('Статичний текст'),
            'heading' => $this->string(255)->null()->comment('H1'),
            'meta_title' => $this->string(255)->null()->comment('Meta title'),
            'meta_description' => $this->text()->null()->comment('Meta description'),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ]);

        $this->createIndex('idx-poll_static_text-poll_id', '{{%poll_static_text}}', 'poll_id', true);
        $this->addForeignKey(
            'fk-poll_static_text-poll_id',
            '{{%poll_static_text}}',
            'poll_id',
            '{{%poll}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        // Видаляємо таблицю статичного тексту опитувань.
        $this->dropForeignKey('fk-poll_static_text-poll_id', '{{%poll_static_text}}');
        $this->dropIndex('idx-poll_static_text-poll_id', '{{%poll_static_text}}');
        $this->dropTable('{{%poll_static_text}}');
    }
}
