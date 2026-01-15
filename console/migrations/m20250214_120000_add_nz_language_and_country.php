<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Додає мовну версію NZ та перевіряє наявність країни "Новая Зеландия".
 */
class m20250214_120000_add_nz_language_and_country extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $timestamp = time();

        // Додаємо країну "Новая Зеландия", якщо вона ще відсутня.
        $countryExists = (new Query())
            ->from('{{%country}}')
            ->where(['name' => 'Новая Зеландия'])
            ->exists();

        if (!$countryExists) {
            $this->insert('{{%country}}', [
                'name' => 'Новая Зеландия',
                'sorting_uk' => 415,
                'sorting_ru' => 430,
                'sorting_en' => 400,
                'sorting_no' => 425,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }

        // Додаємо мову NZ як окрему версію з піддоменом.
        $languageExists = (new Query())
            ->from('{{%language}}')
            ->where(['name' => 'nz'])
            ->exists();

        if (!$languageExists) {
            $idBusy = (new Query())
                ->from('{{%language}}')
                ->where(['id' => 5])
                ->exists();

            if ($idBusy) {
                // Не нашкодити: зупиняємо міграцію, якщо ID 5 вже зайнятий іншою мовою.
                throw new RuntimeException('Неможливо додати мову NZ: ID 5 вже зайнятий.');
            }

            $this->insert('{{%language}}', [
                'id' => 5,
                'name' => 'nz',
                'locale' => 'en-NZ',
                'title' => 'English (New Zealand)',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Перевіряємо, чи NZ вже використовується у пов'язаних таблицях.
        $hasDependencies = (new Query())->from('{{%poll}}')->where(['poll_language_id' => 5])->exists()
            || (new Query())->from('{{%tag}}')->where(['language_id' => 5])->exists()
            || (new Query())->from('{{%page}}')->where(['language_id' => 5])->exists()
            || (new Query())->from('{{%poll_static_text}}')->where(['language_id' => 5])->exists()
            || (new Query())->from('{{%tag_static_text}}')->where(['language_id' => 5])->exists()
            || (new Query())->from('{{%user_language}}')->where(['language_id' => 5])->exists();

        if ($hasDependencies) {
            // Не нашкодити: не видаляємо мову, якщо є залежні записи.
            return false;
        }

        // При відкаті прибираємо лише мову NZ; країну не чіпаємо, щоб не втратити дані.
        $this->delete('{{%language}}', ['name' => 'nz']);

        return true;
    }
}
