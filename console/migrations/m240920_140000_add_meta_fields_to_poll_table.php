<?php

use yii\db\Migration;

class m240920_140000_add_meta_fields_to_poll_table extends Migration
{
    public function safeUp()
    {
        // Додаємо поля для мета-тегів сторінки опитування.
        $this->addColumn('{{%poll}}', 'heading', $this->string(255)->null()->comment('H1'));
        $this->addColumn('{{%poll}}', 'meta_title', $this->string(255)->null()->comment('Meta title'));
        $this->addColumn('{{%poll}}', 'meta_description', $this->text()->null()->comment('Meta description'));
    }

    public function safeDown()
    {
        // Повертаємо схему до попереднього стану.
        $this->dropColumn('{{%poll}}', 'meta_description');
        $this->dropColumn('{{%poll}}', 'meta_title');
        $this->dropColumn('{{%poll}}', 'heading');
    }
}
