<?php

use yii\db\Migration;

/**
 * Додає поля для H1, title та description на сторінці тегів.
 */
class m20241010_120500_add_meta_fields_to_tag_static_text extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tag_static_text}}', 'heading', $this->string(255)->null()->after('content'));
        $this->addColumn('{{%tag_static_text}}', 'meta_title', $this->string(255)->null()->after('heading'));
        $this->addColumn('{{%tag_static_text}}', 'meta_description', $this->text()->null()->after('meta_title'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tag_static_text}}', 'meta_description');
        $this->dropColumn('{{%tag_static_text}}', 'meta_title');
        $this->dropColumn('{{%tag_static_text}}', 'heading');
    }
}
