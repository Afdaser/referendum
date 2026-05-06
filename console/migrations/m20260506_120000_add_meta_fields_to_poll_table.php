<?php

use yii\db\Migration;

class m20260506_120000_add_meta_fields_to_poll_table extends Migration
{
    public function safeUp()
    {
        // Додаємо окремі SEO-поля для кожного опитування, щоб керувати мета-даними з адмінки.
        $this->addColumn('{{%poll}}', 'meta_h1', $this->string()->null()->after('describe')->comment('Meta H1'));
        $this->addColumn('{{%poll}}', 'meta_title', $this->string()->null()->after('meta_h1')->comment('Meta title'));
        $this->addColumn('{{%poll}}', 'meta_description', $this->text()->null()->after('meta_title')->comment('Meta description'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%poll}}', 'meta_description');
        $this->dropColumn('{{%poll}}', 'meta_title');
        $this->dropColumn('{{%poll}}', 'meta_h1');
    }
}
