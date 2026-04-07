<?php

use yii\db\Migration;

/**
 * Створює таблицю налаштувань cookie consent для піддоменів/локалей.
 */
class m20260407_120000_create_table_cookie_consent_setting extends Migration
{
    private const TABLE_NAME = '{{%cookie_consent_setting}}';

    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            // Хост піддомену, напр. en.referendum.social; "*" означає дефолт для всіх хостів.
            'host' => $this->string(255)->notNull(),
            // Локаль, напр. en-US; порожній рядок означає всі локалі.
            'language_code' => $this->string(32)->notNull()->defaultValue(''),
            // Текст банера (короткий опис), який адміністратор задає вручну.
            'banner_text' => $this->text()->notNull(),
            // URL сторінки Cookie Policy (може бути відносний або абсолютний).
            'policy_url' => $this->string(2048)->notNull(),
            'essential_button_label' => $this->string(100)->notNull()->defaultValue('Essential only'),
            'accept_button_label' => $this->string(100)->notNull()->defaultValue('Accept all'),
            'is_active' => $this->boolean()->notNull()->defaultValue(1),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ]);

        // Один запис на пару host + language_code, щоб уникати конфліктних налаштувань.
        $this->createIndex(
            'ux_cookie_consent_setting_host_language_code',
            self::TABLE_NAME,
            ['host', 'language_code'],
            true
        );
        $this->createIndex('ix_cookie_consent_setting_is_active', self::TABLE_NAME, 'is_active');
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}

