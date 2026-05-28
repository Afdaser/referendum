<?php

use yii\db\Migration;

class m20260528_120000_add_image_to_news_table extends Migration
{
    public function safeUp()
    {
        // Додаємо необов'язкове hero-зображення для детальної сторінки новини.
        $this->addColumn(
            '{{%news}}',
            'image',
            $this->string(255)->null()->after('desc')->comment('Hero image URL or path')
        );
    }

    public function safeDown()
    {
        $this->dropColumn('{{%news}}', 'image');
    }
}
