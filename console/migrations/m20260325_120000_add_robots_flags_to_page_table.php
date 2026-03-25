<?php

use yii\db\Migration;

class m20260325_120000_add_robots_flags_to_page_table extends Migration
{
    public function safeUp()
    {
        // Додаємо керовані SEO-прапорці для meta robots на рівні окремої сторінки.
        $this->addColumn('{{%page}}', 'robots_index', $this->string(10)->notNull()->defaultValue('index')->comment('Robots index'));
        $this->addColumn('{{%page}}', 'robots_follow', $this->string(10)->notNull()->defaultValue('follow')->comment('Robots follow'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%page}}', 'robots_follow');
        $this->dropColumn('{{%page}}', 'robots_index');
    }
}
