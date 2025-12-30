<?php

use yii\db\Migration;

/**
 * Додає поле для статичного HTML-блоку до таблиці poll_static_text.
 */
class m20241012_120001_add_content_to_poll_static_text extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Додаємо окремий текстовий стовпець для HTML-блоку статистики опитувань.
        $this->addColumn('{{%poll_static_text}}', 'content', $this->text()->null()->after('language_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%poll_static_text}}', 'content');
    }
}
