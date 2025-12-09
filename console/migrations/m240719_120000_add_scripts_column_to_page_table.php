<?php

use yii\db\Migration;

class m240719_120000_add_scripts_column_to_page_table extends Migration
{
    public function safeUp()
    {
        // Додаємо текстове поле для зберігання індивідуальних скриптів сторінки.
        $this->addColumn('{{%page}}', 'scripts', $this->text()->comment('Scripts'));
    }

    public function safeDown()
    {
        // Повертаємо схему до попереднього стану, прибираючи нове поле.
        $this->dropColumn('{{%page}}', 'scripts');
    }
}
