<?php

use yii\db\Migration;
use yii\db\Query;
use common\helpers\UsStates;

/** Run with all web/queue writers stopped. Retains original tables as archives. */
class m261102_000001_unify_poll_votes extends Migration
{
    public function up()
    {
        if ($this->db->driverName !== 'mysql') {
            throw new RuntimeException('This migration requires MySQL/MariaDB.');
        }
        foreach (['poll_vote', 'option_vote_archive', 'option_guest_vote_archive'] as $table) {
            if ($this->db->schema->getTableSchema('{{%' . $table . '}}', true) !== null) {
                throw new RuntimeException('Existing migration staging/archive table: ' . $table . '. Inspect the previous run before retrying; do not delete vote data.');
            }
        }
        $countries = (new Query())->from('{{%country}}')->where(['name' => [
            'US', 'USA', 'United States', 'United States of America', 'США',
            'Сполучені Штати Америки', 'Соединенные Штаты Америки', 'Соединённые Штаты Америки',
        ]])->all($this->db);
        if (count($countries) > 1) {
            throw new RuntimeException('Multiple US country records: resolve the dictionary duplicates before migrating.');
        }
        $countryId = $countries ? (int) $countries[0]['id'] : null;
        $matches = [];
        foreach (UsStates::all() as $code => $name) {
            $aliases = [$name, $code, 'US-' . $code];
            $aliases = array_merge($aliases, explode('|', $this->stateAliases()[$code]));
            $rows = $countryId ? (new Query())->from('{{%country_region}}')
                ->where(['country_id' => $countryId, 'name' => $aliases])->all($this->db) : [];
            if (count($rows) > 1) {
                throw new RuntimeException('Multiple region records for US-' . $code . ': resolve duplicates before migrating.');
            }
            $matches[$code] = $rows ? (int) $rows[0]['id'] : null;
        }
        // Never silently create a second location for an existing profile's state.
        if ($countryId) {
            $unmappedProfiles = (new Query())->from('{{%profile}} p')
                ->where(['p.country_id' => $countryId])->andWhere(['>', 'p.region_id', 0])
                ->andWhere(['not in', 'p.region_id', array_values(array_filter($matches))])
                ->select('p.region_id')->distinct()->column($this->db);
            if ($unmappedProfiles) {
                throw new RuntimeException('US profile regions need explicit dictionary mapping before migration: '
                    . implode(', ', $unmappedProfiles) . '. See docs/unified-votes.md.');
            }
        }
        // Preflight rejects unrecognised confirmed state codes rather than losing them.
        $invalid = (new Query())->from('{{%option_guest_vote}}')->where(['not', ['state_code' => null]])
            ->andWhere(['or', ['not in', 'state_code', array_keys(UsStates::all())],
                ['not', ['country_code' => 'US']], ['country_code' => null]])->exists($this->db);
        if ($invalid) {
            throw new RuntimeException('Unrecognised guest location codes; inspect them before migrating.');
        }

        foreach (['option_vote', 'option_guest_vote'] as $source) {
            if ((new Query())->from(['v' => '{{%' . $source . '}}'])
                ->leftJoin('{{%poll_option}} po', 'po.id = v.option_id')
                ->where(['po.id' => null])->exists($this->db)) {
                throw new RuntimeException('Orphaned votes in ' . $source . '; repair references before migrating.');
            }
        }
        $this->addColumn('{{%country}}', 'iso_code', $this->string(2)->null());
        $this->createIndex('uq_country_iso_code', '{{%country}}', 'iso_code', true);
        $this->addColumn('{{%country_region}}', 'code', $this->string(16)->null());
        $this->createIndex('uq_country_region_code', '{{%country_region}}', ['country_id', 'code'], true);
        if (!$countryId) {
            $this->insert('{{%country}}', ['name' => 'United States', 'iso_code' => 'US']);
            $countryId = (int) $this->db->getLastInsertID();
        } else {
            $this->update('{{%country}}', ['iso_code' => 'US'], ['id' => $countryId]);
        }
        foreach (UsStates::all() as $code => $name) {
            if ($matches[$code]) {
                $this->update('{{%country_region}}', ['code' => $code], ['id' => $matches[$code]]);
            } else {
                $this->insert('{{%country_region}}', ['country_id' => $countryId, 'name' => $name, 'code' => $code]);
            }
        }

        $this->createTable('{{%poll_vote}}', [
            'id' => $this->primaryKey()->unsigned(),
            'option_id' => $this->integer()->unsigned()->notNull(),
            'poll_id' => $this->integer()->unsigned()->notNull(),
            'is_guest' => $this->boolean()->notNull()->defaultValue(0),
            'user_id' => $this->integer()->unsigned()->null(),
            'user_ip' => $this->bigInteger()->null(),
            'ip_of_user' => $this->string(67)->null(),
            'guest_ip_key' => $this->string(67)->null(),
            // Unique locks preserve historical duplicates without allowing new ones.
            'guest_lock_key' => $this->string(67)->null(),
            'registered_lock_key' => $this->integer()->unsigned()->null(),
            'sex_at_birth' => $this->smallInteger()->null(),
            'age_interval' => $this->smallInteger()->null(),
            'country_id' => $this->integer()->unsigned()->null(),
            'region_id' => $this->integer()->unsigned()->null(),
            // Retained for audit/backward compatibility; filtering uses dictionary IDs.
            'country_code' => $this->string(2)->null(),
            'state_code' => $this->string(2)->null(),
            'legacy_guest_id' => $this->integer()->unsigned()->null(),
            'date_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer()->unsigned()->null(),
            'updated_by' => $this->integer()->unsigned()->null(),
            'created_at' => $this->integer()->unsigned()->null(),
            'updated_at' => $this->integer()->unsigned()->null(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        $this->createIndex('uq_poll_vote_legacy_guest', '{{%poll_vote}}', 'legacy_guest_id', true);
        $this->createIndex('uq_poll_vote_guest', '{{%poll_vote}}', ['poll_id', 'guest_lock_key'], true);
        $this->createIndex('uq_poll_vote_registered', '{{%poll_vote}}', ['poll_id', 'registered_lock_key'], true);
        $this->createIndex('idx_poll_vote_option_type', '{{%poll_vote}}', ['option_id', 'is_guest']);
        $this->createIndex('idx_poll_vote_guest_lookup', '{{%poll_vote}}', ['poll_id', 'guest_ip_key']);
        $this->createIndex('idx_poll_vote_location', '{{%poll_vote}}', ['country_id', 'region_id']);
        $this->addForeignKey('fk_poll_vote_option', '{{%poll_vote}}', 'option_id', '{{%poll_option}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_poll_vote_poll', '{{%poll_vote}}', 'poll_id', '{{%poll}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_poll_vote_user', '{{%poll_vote}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_poll_vote_country', '{{%poll_vote}}', 'country_id', '{{%country}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_poll_vote_region', '{{%poll_vote}}', 'region_id', '{{%country_region}}', 'id', 'RESTRICT', 'CASCADE');

        $transaction = $this->db->beginTransaction();
        try {
            $this->execute('INSERT INTO {{%poll_vote}} (id, option_id, poll_id, is_guest, user_id, user_ip, date_add, created_by, updated_by, created_at, updated_at)
                SELECT v.id, v.option_id, po.poll_id, 0, v.user_id, v.user_ip, v.date_add, v.created_by, v.updated_by, v.created_at, v.updated_at
                FROM {{%option_vote}} v JOIN {{%poll_option}} po ON po.id = v.option_id');
            $this->execute('INSERT INTO {{%poll_vote}} (option_id, poll_id, is_guest, user_ip, ip_of_user, guest_ip_key,
                    sex_at_birth, age_interval, country_code, state_code, country_id, region_id, legacy_guest_id,
                    date_add, created_by, updated_by, created_at, updated_at)
                SELECT v.option_id, po.poll_id, 1, v.user_ip, v.ip_of_user, v.guest_ip_key,
                    v.sex_at_birth, v.age_interval, IF(r.id IS NULL, NULL, v.country_code), v.state_code,
                    r.country_id, r.id, v.id, v.date_add, v.created_by, v.updated_by, v.created_at, v.updated_at
                FROM {{%option_guest_vote}} v JOIN {{%poll_option}} po ON po.id = v.option_id
                LEFT JOIN {{%country}} c ON c.iso_code = v.country_code
                LEFT JOIN {{%country_region}} r ON r.country_id = c.id AND r.code = v.state_code
                ORDER BY v.id');
            // Only one historical row per identity owns the unique lock. Every vote is kept.
            $this->execute('UPDATE {{%poll_vote}} v JOIN (
                SELECT MIN(id) id FROM (SELECT id, poll_id, guest_ip_key FROM {{%poll_vote}} WHERE is_guest = 1) g
                GROUP BY poll_id, guest_ip_key) first_vote ON first_vote.id = v.id SET v.guest_lock_key = v.guest_ip_key');
            $this->execute('UPDATE {{%poll_vote}} v JOIN (
                SELECT MIN(id) id FROM (SELECT id, poll_id, user_id FROM {{%poll_vote}} WHERE is_guest = 0) u
                GROUP BY poll_id, user_id) first_vote ON first_vote.id = v.id SET v.registered_lock_key = v.user_id');
            foreach ([0 => 'option_vote', 1 => 'option_guest_vote'] as $type => $table) {
                $before = (int) (new Query())->from('{{%' . $table . '}}')->count('*', $this->db);
                $after = (int) (new Query())->from('{{%poll_vote}}')->where(['is_guest' => $type])->count('*', $this->db);
                if ($before !== $after) {
                    throw new RuntimeException('Vote count mismatch for ' . $table . '; source tables have not been switched.');
                }
            }
            $transaction->commit();
        } catch (Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
        // One atomic rename preserves the original data for manual recovery.
        $this->execute('RENAME TABLE {{%option_vote}} TO {{%option_vote_archive}}, {{%option_guest_vote}} TO {{%option_guest_vote_archive}}');
        foreach (['option_vote_archive', 'option_guest_vote_archive'] as $table) {
            // Archives must not block later deletion of a live poll/user.
            foreach ($this->db->schema->getTableSchema('{{%' . $table . '}}', true)->foreignKeys as $name => $definition) {
                $this->dropForeignKey($name, '{{%' . $table . '}}');
            }
        }
        $this->execute('CREATE ALGORITHM=MERGE VIEW {{%option_vote}} AS SELECT * FROM {{%poll_vote}} WHERE is_guest = 0 WITH CASCADED CHECK OPTION');
        $this->execute('CREATE ALGORITHM=MERGE VIEW {{%option_guest_vote}} AS SELECT * FROM {{%poll_vote}} WHERE is_guest = 1 WITH CASCADED CHECK OPTION');
        $this->db->schema->refresh();
    }

    public function down()
    {
        echo "This data migration is forward-only. Do not restore archives over new votes. See docs/unified-votes.md.\n";
        return false;
    }

    private function stateAliases(): array
    {
        return [
            'AL'=>'Алабама','AK'=>'Аляска','AZ'=>'Аризона','AR'=>'Арканзас','CA'=>'Калифорния|Каліфорнія',
            'CO'=>'Колорадо','CT'=>'Коннектикут','DE'=>'Делавэр|Делавер','DC'=>'Округ Колумбия|Округ Колумбія|Вашингтон, округ Колумбия|Washington, D.C.',
            'FL'=>'Флорида|Флорида','GA'=>'Джорджия|Джорджія','HI'=>'Гавайи|Гаваї','ID'=>'Айдахо','IL'=>'Иллинойс|Іллінойс',
            'IN'=>'Индиана|Індіана','IA'=>'Айова','KS'=>'Канзас','KY'=>'Кентукки|Кентуккі','LA'=>'Луизиана|Луїзіана',
            'ME'=>'Мэн|Мен','MD'=>'Мэриленд|Меріленд','MA'=>'Массачусетс','MI'=>'Мичиган|Мічиган','MN'=>'Миннесота|Міннесота',
            'MS'=>'Миссисипи|Міссісіпі','MO'=>'Миссури|Міссурі','MT'=>'Монтана','NE'=>'Небраска','NV'=>'Невада',
            'NH'=>'Нью-Гэмпшир|Нью-Гемпшир','NJ'=>'Нью-Джерси|Нью-Джерсі','NM'=>'Нью-Мексико','NY'=>'Нью-Йорк',
            'NC'=>'Северная Каролина|Північна Кароліна','ND'=>'Северная Дакота|Північна Дакота','OH'=>'Огайо',
            'OK'=>'Оклахома','OR'=>'Орегон','PA'=>'Пенсильвания|Пенсільванія|Пенсильванія','RI'=>'Род-Айленд',
            'SC'=>'Южная Каролина|Південна Кароліна','SD'=>'Южная Дакота|Південна Дакота','TN'=>'Теннесси|Теннессі',
            'TX'=>'Техас','UT'=>'Юта','VT'=>'Вермонт','VA'=>'Виргиния|Вірджинія','WA'=>'Вашингтон',
            'WV'=>'Западная Виргиния|Західна Вірджинія','WI'=>'Висконсин|Вісконсин','WY'=>'Вайоминг|Вайомінг',
        ];
    }
}
