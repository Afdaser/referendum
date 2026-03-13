<?php

use yii\db\Migration;

/**
 * Додає окремі SEO-поля для сторінок пагінації на головній і на сторінці тегу.
 */
class m20260313_120000_add_paginated_meta_fields_for_main_and_tag_pages extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Для головної сторінки: окремі шаблони мета-тегів саме для /page/N.
        $this->addColumn('{{%main_page_seo_text}}', 'paginated_meta_title', $this->string(255)->null()->after('meta_description'));
        $this->addColumn('{{%main_page_seo_text}}', 'paginated_meta_description', $this->text()->null()->after('paginated_meta_title'));

        // Для сторінки тегу: окремі шаблони мета-тегів для пагінації в межах кожної мови.
        $this->addColumn('{{%tag_static_text}}', 'paginated_meta_title', $this->string(255)->null()->after('meta_description'));
        $this->addColumn('{{%tag_static_text}}', 'paginated_meta_description', $this->text()->null()->after('paginated_meta_title'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tag_static_text}}', 'paginated_meta_description');
        $this->dropColumn('{{%tag_static_text}}', 'paginated_meta_title');
        $this->dropColumn('{{%main_page_seo_text}}', 'paginated_meta_description');
        $this->dropColumn('{{%main_page_seo_text}}', 'paginated_meta_title');
    }
}

