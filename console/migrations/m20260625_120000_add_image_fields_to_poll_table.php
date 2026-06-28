<?php

use yii\db\Migration;

/**
 * Додає одне зображення до опитування: URL файлу та альтернативний текст.
 */
class m20260625_120000_add_image_fields_to_poll_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%poll}}', 'image_url', $this->string(1024)->null()->after('meta_description'));
        $this->addColumn('{{%poll}}', 'image_alt', $this->string(255)->null()->after('image_url'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%poll}}', 'image_alt');
        $this->dropColumn('{{%poll}}', 'image_url');
    }
}
