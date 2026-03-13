<?php

use yii\db\Migration;

/**
 * Додає окремі H1-шаблони для сторінок пагінації на головній та сторінках тегів.
 */
class m20260313_130000_add_paginated_heading_fields_for_main_and_tag_pages extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Для головної сторінки: окремий H1-шаблон саме для /page/N.
        $this->addColumn('{{%main_page_seo_text}}', 'paginated_heading', $this->string(255)->null()->after('heading'));

        // Для сторінки тегу: окремий H1-шаблон для пагінації в межах кожної мови.
        $this->addColumn('{{%tag_static_text}}', 'paginated_heading', $this->string(255)->null()->after('heading'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tag_static_text}}', 'paginated_heading');
        $this->dropColumn('{{%main_page_seo_text}}', 'paginated_heading');
    }
}

