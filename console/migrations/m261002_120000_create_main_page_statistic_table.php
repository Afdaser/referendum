<?php

use yii\db\Migration;

/**
 * Створює компактний кеш показників, який безпечно оновлювати через cron.
 */
class m261002_120000_create_main_page_statistic_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%main_page_statistic}}', [
            'id' => $this->primaryKey()->unsigned(),
            // NULL зарезервовано для загального підсумку головного домену.
            'language_id' => $this->integer()->unsigned()->null(),
            'users_count' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'polls_count' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'votes_count' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->createIndex('idx-main_page_statistic-language_id', '{{%main_page_statistic}}', 'language_id', true);
        $this->addForeignKey(
            'fk-main_page_statistic-language_id',
            '{{%main_page_statistic}}',
            'language_id',
            '{{%language}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%main_page_statistic}}');
    }
}
