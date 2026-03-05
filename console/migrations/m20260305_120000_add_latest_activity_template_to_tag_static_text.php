<?php

use yii\db\Migration;

/**
 * Додає поле з HTML-шаблоном блоку "Latest activity" для сторінки тегу.
 */
class m20260305_120000_add_latest_activity_template_to_tag_static_text extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Нове поле дозволяє керувати вмістом блоку активності через адмінку.
        $this->addColumn('{{%tag_static_text}}', 'latest_activity_template', $this->text()->null()->after('meta_description'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tag_static_text}}', 'latest_activity_template');
    }
}
