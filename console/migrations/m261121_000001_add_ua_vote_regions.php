<?php

use common\helpers\UaRegions;
use yii\db\Migration;
use yii\db\Query;

/** Зберігає наявні ID областей України для профілів і голосів. */
class m261121_000001_add_ua_vote_regions extends Migration
{
    public function up()
    {
        $countries = (new Query())->from('{{%country}}')->where(['or',
            ['iso_code' => 'UA'],
            ['name' => ['Україна', 'Украина', 'Ukraine', 'UA']],
        ])->all($this->db);
        if (count($countries) > 1) {
            throw new RuntimeException('Знайдено кілька записів країни UA; спершу усуньте дублікати в довіднику.');
        }
        $countryId = $countries ? (int) $countries[0]['id'] : null;
        if ($countries && !in_array($countries[0]['iso_code'], [null, '', 'UA'], true)) {
            throw new RuntimeException('Запис України має інший код ISO; перевірте довідник країн.');
        }

        $matches = [];
        $matchedIds = [];
        $aliases = $this->regionAliases();
        foreach (UaRegions::all() as $rawCode => $name) {
            $code = (string) $rawCode;
            $names = array_merge([$name, $code, 'UA-' . $code], $aliases[$code] ?? []);
            $rows = $countryId ? (new Query())->from('{{%country_region}}')
                ->where(['country_id' => $countryId])
                ->andWhere(['or', ['code' => [$code, 'UA-' . $code]], ['name' => $names]])
                ->all($this->db) : [];
            if (count($rows) > 1) {
                throw new RuntimeException('Кілька записів для UA-' . $code . '; усуньте дублікати областей.');
            }
            if ($rows && !in_array($rows[0]['code'], [null, '', $code, 'UA-' . $code], true)) {
                throw new RuntimeException('Конфліктний код області UA-' . $code . '; перевірте довідник.');
            }
            $regionId = $rows ? (int) $rows[0]['id'] : null;
            if ($regionId !== null && in_array($regionId, $matchedIds, true)) {
                throw new RuntimeException('Один запис області зіставлено з кількома кодами UA.');
            }
            $matches[$code] = $regionId;
            if ($regionId !== null) {
                $matchedIds[] = $regionId;
            }
        }

        // Не створюємо паралельні області для наявних профілів і голосів.
        if ($countryId) {
            foreach (['profile', 'poll_vote'] as $table) {
                $query = (new Query())->from('{{%' . $table . '}}')
                    ->where(['country_id' => $countryId])->andWhere(['>', 'region_id', 0]);
                if ($matchedIds) {
                    $query->andWhere(['not in', 'region_id', $matchedIds]);
                }
                $unmapped = $query->select('region_id')->distinct()->column($this->db);
                if ($unmapped) {
                    throw new RuntimeException('Невідомі області UA у ' . $table . ': ' . implode(', ', $unmapped));
                }
            }
        }

        $transaction = $this->db->beginTransaction();
        try {
            if ($countryId === null) {
                $this->insert('{{%country}}', ['name' => 'Україна', 'iso_code' => 'UA']);
                $countryId = (int) $this->db->getLastInsertID();
            } else {
                $this->update('{{%country}}', ['iso_code' => 'UA'], ['id' => $countryId]);
            }
            foreach (UaRegions::all() as $rawCode => $name) {
                $code = (string) $rawCode;
                if ($matches[$code] !== null) {
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
        echo "Області України можуть використовуватися профілями та голосами; цю міграцію не відкочують.\n";
        return false;
    }

    private function regionAliases(): array
    {
        return [
            '43' => ['АР Крим', 'АР Крым', 'Автономная Республика Крым', 'Крим', 'Крым'],
            '05' => ['Вінницька', 'Винницкая область', 'Винницкая'],
            '07' => ['Волинська', 'Волынская область', 'Волынская'],
            '12' => ['Дніпропетровська', 'Днепропетровская область', 'Днепропетровская'],
            '14' => ['Донецька', 'Донецкая область', 'Донецкая'],
            '18' => ['Житомирська', 'Житомирская область', 'Житомирская'],
            '21' => ['Закарпатська', 'Закарпатская область', 'Закарпатская'],
            '23' => ['Запорізька', 'Запорожская область', 'Запорожская'],
            '26' => ['Івано-Франківська', 'Ивано-Франковская область', 'Ивано-Франковская'],
            '32' => ['Київська', 'Киевская область', 'Киевская'],
            '35' => ['Кіровоградська', 'Кировоградская область', 'Кировоградская'],
            '09' => ['Луганська', 'Луганская область', 'Луганская'],
            '46' => ['Львівська', 'Львовская область', 'Львовская'],
            '48' => ['Миколаївська', 'Николаевская область', 'Николаевская'],
            '51' => ['Одеська', 'Одесская область', 'Одесская'],
            '53' => ['Полтавська', 'Полтавская область', 'Полтавская'],
            '56' => ['Рівненська', 'Ровенская область', 'Ровенская'],
            '59' => ['Сумська', 'Сумская область', 'Сумская'],
            '61' => ['Тернопільська', 'Тернопольская область', 'Тернопольская'],
            '63' => ['Харківська', 'Харьковская область', 'Харьковская'],
            '65' => ['Херсонська', 'Херсонская область', 'Херсонская'],
            '68' => ['Хмельницька', 'Хмельницкая область', 'Хмельницкая'],
            '71' => ['Черкаська', 'Черкасская область', 'Черкасская'],
            '74' => ['Чернігівська', 'Черниговская область', 'Черниговская'],
            '77' => ['Чернівецька', 'Черновицкая область', 'Черновицкая'],
            '30' => ['Київ', 'Киев', 'м. Київ', 'м. Киев', 'місто Київ', 'город Киев', 'г. Киев'],
            '40' => ['Севастополь', 'м. Севастополь', 'город Севастополь', 'г. Севастополь'],
        ];
    }
}
