<?php

use common\helpers\NzRegions;
use yii\db\Migration;
use yii\db\Query;

/** Reuse existing NZ dictionary IDs so profiles and guest votes share regions. */
class m261110_000001_add_nz_vote_regions extends Migration
{
    public function up()
    {
        $countries = (new Query())->from('{{%country}}')->where(['or', ['iso_code' => 'NZ'],
            ['name' => ['NZ', 'New Zealand', 'Новая Зеландия', 'Нова Зеландія']]])->all($this->db);
        if (count($countries) > 1) {
            throw new RuntimeException('Multiple NZ country records; resolve dictionary duplicates before migrating.');
        }
        $countryId = $countries ? (int) $countries[0]['id'] : null;
        if ($countries && !in_array($countries[0]['iso_code'], [null, '', 'NZ'], true)) {
            throw new RuntimeException('NZ country record has a different ISO code; inspect the dictionary.');
        }
        $aliases = $this->regionAliases();
        $matches = [];
        foreach (NzRegions::all() as $code => $name) {
            $names = array_merge([$name, $name . ' Region', $code, 'NZ-' . $code], $aliases[$code] ?? []);
            $rows = $countryId ? (new Query())->from('{{%country_region}}')
                ->where(['country_id' => $countryId])
                ->andWhere(['or', ['code' => $code], ['name' => $names]])->all($this->db) : [];
            if (count($rows) > 1) {
                throw new RuntimeException('Multiple region records for NZ-' . $code . '; resolve duplicates before migrating.');
            }
            if ($rows && !in_array($rows[0]['code'], [null, '', $code], true)) {
                throw new RuntimeException('Conflicting region code for NZ-' . $code . '; inspect the dictionary.');
            }
            $matches[$code] = $rows ? (int) $rows[0]['id'] : null;
        }
        // Do not silently split locations already used by profiles or votes.
        if ($countryId) {
            foreach (['profile', 'poll_vote'] as $table) {
                $unmapped = (new Query())->from('{{%' . $table . '}}')
                    ->where(['country_id' => $countryId])->andWhere(['>', 'region_id', 0])
                    ->andWhere(['not in', 'region_id', array_values(array_filter($matches))])
                    ->select('region_id')->distinct()->column($this->db);
                if ($unmapped) {
                    throw new RuntimeException('NZ regions need explicit dictionary mapping: ' . implode(', ', $unmapped));
                }
            }
        }

        // MySQL DDL commits independently; widening is safe to retry if seeding fails.
        // Keep the legacy field name used by the compatibility views and POST forms.
        $this->alterColumn('{{%poll_vote}}', 'state_code', $this->string(16)->null());
        $transaction = $this->db->beginTransaction();
        try {
            if (!$countryId) {
                $this->insert('{{%country}}', ['name' => 'New Zealand', 'iso_code' => 'NZ']);
                $countryId = (int) $this->db->getLastInsertID();
            } else {
                $this->update('{{%country}}', ['iso_code' => 'NZ'], ['id' => $countryId]);
            }
            foreach (NzRegions::all() as $code => $name) {
                if ($matches[$code]) {
                    $this->update('{{%country_region}}', ['name' => $name, 'code' => $code], ['id' => $matches[$code]]);
                } else {
                    $this->insert('{{%country_region}}', ['country_id' => $countryId, 'name' => $name, 'code' => $code]);
                }
            }
            $transaction->commit();
        } catch (Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
        $this->db->schema->refresh();
    }

    public function down()
    {
        echo "NZ regions may be referenced by profiles or votes; this migration is forward-only.\n";
        return false;
    }

    private function regionAliases(): array
    {
        return [
            'AUK' => ['Окленд'],
            'BOP' => ['Бей-оф-Пленти', 'Бей-оф-Пленті'],
            'CAN' => ['Кентербери', 'Кентербері'],
            'CIT' => ['Chatham Islands Territory', 'Chatham Islands Council', 'Острова Чатем', 'Острови Чатем'],
            'GIS' => ['Гисборн', 'Гісборн'],
            'HKB' => ['Hawkes Bay', 'Hawke’s Bay', 'Хокс-Бей'],
            'MWT' => ['Manawatu-Wanganui', 'Manawatu-Whanganui', 'Manawatu Wanganui', 'Манавату-Уангануи', 'Манавату-Вангануї'],
            'MBH' => ['Марлборо'],
            'NSN' => ['Нельсон'],
            'NTL' => ['Нортленд'],
            'OTA' => ['Отаго'],
            'STL' => ['Саутленд'],
            'TKI' => ['Таранаки', 'Таранакі'],
            'TAS' => ['Тасман'],
            'WKO' => ['Уаикато', 'Ваїкато'],
            'WGN' => ['Greater Wellington', 'Веллингтон', 'Веллінгтон'],
            'WTC' => ['Вест-Кост'],
        ];
    }
}
