<?php

use yii\db\Migration;

class m20260407_130000_add_robots_to_page_table extends Migration
{
    public function safeUp()
    {
        // Додаємо поле для керування meta robots у статичних сторінках.
        $this->addColumn('{{%page}}', 'robots', $this->string(32)->notNull()->defaultValue('index, follow')->after('describe')->comment('Robots'));
    }

    public function safeDown()
    {
        // Відкат без побічних дій: просто прибираємо додану колонку.
        $this->dropColumn('{{%page}}', 'robots');
    }
}
