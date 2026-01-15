<?php

use yii\db\Migration;
use yii\db\Query;

class m20260115_162319_add_nz_language_and_country extends Migration
{
    // Фіксуємо час створення записів, щоб безпечно відкотити лише цю міграцію.
    private const MIGRATION_CREATED_AT = 1768494241;

    public function safeUp()
    {
        // Додаємо Нову Зеландію до довідника країн, якщо її ще немає.
        $countryName = 'Новая Зеландия';
        $countryExists = (new Query())
            ->from('{{%country}}')
            ->where(['name' => $countryName])
            ->exists($this->db);

        if (!$countryExists) {
            $this->insert('{{%country}}', [
                'name' => $countryName,
                'sorting_uk' => 0,
                'sorting_ru' => 0,
                'sorting_en' => 0,
                'sorting_no' => 0,
                'created_at' => self::MIGRATION_CREATED_AT,
                'updated_at' => self::MIGRATION_CREATED_AT,
            ]);
        }

        // Додаємо мову для NZ із локаллю en-NZ, якщо її ще немає.
        $languageName = 'nz';
        $languageLocale = 'en-NZ';
        $languageTitle = 'English (NZ)';
        $languageExists = (new Query())
            ->from('{{%language}}')
            ->where(['name' => $languageName])
            ->orWhere(['locale' => $languageLocale])
            ->exists($this->db);

        if (!$languageExists) {
            $this->insert('{{%language}}', [
                'name' => $languageName,
                'locale' => $languageLocale,
                'title' => $languageTitle,
                'created_at' => self::MIGRATION_CREATED_AT,
                'updated_at' => self::MIGRATION_CREATED_AT,
            ]);
        }
    }

    public function safeDown()
    {
        // Прибираємо додані записи, щоб відкотити міграцію без побічних ефектів.
        $this->delete('{{%language}}', [
            'name' => 'nz',
            'created_at' => self::MIGRATION_CREATED_AT,
        ]);
        $this->delete('{{%country}}', [
            'name' => 'Новая Зеландия',
            'created_at' => self::MIGRATION_CREATED_AT,
        ]);
    }
}
