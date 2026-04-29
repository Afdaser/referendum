<?php

use yii\db\Migration;

class m20260429_120000_add_index_tag_name_language_id extends Migration
{
    public function safeUp()
    {
        $this->createIndex(
            'idx_tag_name_language_id',
            '{{%tag}}',
            ['name', 'language_id']
        );
    }

    public function safeDown()
    {
        $this->dropIndex('idx_tag_name_language_id', '{{%tag}}');
    }
}
