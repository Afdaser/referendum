<?php

use yii\db\Migration;

class m20260407_120000_add_robots_tag_to_page_table extends Migration
{
    public function safeUp()
    {
        // Додаємо robots_tag для керування meta robots на статичних сторінках.
        $this->addColumn(
            '{{%page}}',
            'robots_tag',
            $this->string(32)->null()->defaultValue('index, follow')->after('describe')->comment('Robots tag')
        );
    }

    public function safeDown()
    {
        $this->dropColumn('{{%page}}', 'robots_tag');
    }
}
