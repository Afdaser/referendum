<?php

use yii\db\Migration;

class m260316_000002_add_guest_ip_key_unique_for_poll_comment extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%poll_comment}}', 'guest_ip_key', $this->string(67)->null()->after('ip_of_user')->comment('Guest IP key for unique limit'));

        // Нормалізуємо ключ для вже існуючих гостьових коментарів.
        $this->execute("UPDATE {{%poll_comment}} SET guest_ip_key = ip_of_user WHERE is_guest = 1 AND ip_of_user IS NOT NULL AND ip_of_user <> ''");
        $this->execute("UPDATE {{%poll_comment}} SET guest_ip_key = CAST(user_ip AS CHAR) WHERE is_guest = 1 AND (guest_ip_key IS NULL OR guest_ip_key = '') AND user_ip IS NOT NULL");

        // На випадок історичних дублікатів залишаємо найстаріший запис перед встановленням UNIQUE.
        $this->execute(
            "DELETE c1 FROM {{%poll_comment}} c1
             INNER JOIN {{%poll_comment}} c2
               ON c1.poll_id = c2.poll_id
              AND c1.guest_ip_key = c2.guest_ip_key
              AND c1.id > c2.id
             WHERE c1.is_guest = 1
               AND c2.is_guest = 1
               AND c1.guest_ip_key IS NOT NULL
               AND c1.guest_ip_key <> ''"
        );

        // Гарантія на рівні БД: у межах poll один guest_ip_key може бути лише один раз.
        $this->createIndex('ux_poll_comment_poll_guest_ip_key', '{{%poll_comment}}', ['poll_id', 'guest_ip_key'], true);
    }

    public function safeDown()
    {
        $this->dropIndex('ux_poll_comment_poll_guest_ip_key', '{{%poll_comment}}');
        $this->dropColumn('{{%poll_comment}}', 'guest_ip_key');
    }
}

