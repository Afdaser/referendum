<?php

use yii\db\Migration;

class m260318_000001_extend_option_guest_vote_for_ipv6 extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%option_guest_vote}}', 'poll_id', $this->integer()->unsigned()->null()->after('option_id')->comment('Poll'));
        $this->addColumn('{{%option_guest_vote}}', 'guest_ip_key', $this->string(67)->null()->after('ip_of_user')->comment('Guest IP key for unique limit'));

        // Заповнюємо poll_id з пов'язаних опцій, щоб надалі гарантувати унікальність на рівні опитування.
        $this->execute(
            "UPDATE {{%option_guest_vote}} ogv
             INNER JOIN {{%poll_option}} po ON po.id = ogv.option_id
             SET ogv.poll_id = po.poll_id
             WHERE ogv.poll_id IS NULL"
        );

        // Нормалізуємо ключ guest_ip_key для обох протоколів (IPv4/IPv6).
        $this->execute("UPDATE {{%option_guest_vote}} SET guest_ip_key = ip_of_user WHERE ip_of_user IS NOT NULL AND ip_of_user <> ''");
        $this->execute("UPDATE {{%option_guest_vote}} SET guest_ip_key = CAST(user_ip AS CHAR) WHERE (guest_ip_key IS NULL OR guest_ip_key = '') AND user_ip IS NOT NULL");

        // Історичні дублікати навмисно НЕ видаляємо (великий обсяг даних у проді).
        // Очищення дублікатів виконаємо окремою міграцією/скриптом після узгодження вікна обслуговування.

        // Повторний бекфіл перед NOT NULL, щоб закрити вікно між початком міграції та перемиканням всіх app-серверів.
        // Це безпечніше за DELETE і не втрачає голоси, які могли бути записані старим кодом під час rollout.
        $this->execute(
            "UPDATE {{%option_guest_vote}} ogv
             INNER JOIN {{%poll_option}} po ON po.id = ogv.option_id
             SET ogv.poll_id = po.poll_id
             WHERE ogv.poll_id IS NULL"
        );

        // ВАЖЛИВО: poll_id залишаємо NULLABLE у цій міграції.
        // Причина: під час rolling/zero-downtime деплою старі інстанси можуть ще писати рядки без poll_id.
        // NOT NULL слід вмикати окремою "другою фазою" після повного перемикання всіх writer-інстансів.

        $this->createIndex('idx_option_guest_vote_poll_id', '{{%option_guest_vote}}', 'poll_id');
        $this->addForeignKey('fk_option_guest_vote_poll', '{{%option_guest_vote}}', 'poll_id', '{{%poll}}', 'id', 'CASCADE', 'CASCADE');

        // Поки що без UNIQUE, щоб не блокувати міграцію на історичних дублікатах.
        // Індекс залишаємо для швидкої перевірки/пошуку, унікальність додамо окремо після дедуплікації.
        $this->createIndex('idx_option_guest_vote_poll_guest_ip_key', '{{%option_guest_vote}}', ['poll_id', 'guest_ip_key'], false);
    }

    public function safeDown()
    {
        $this->dropIndex('idx_option_guest_vote_poll_guest_ip_key', '{{%option_guest_vote}}');
        $this->dropForeignKey('fk_option_guest_vote_poll', '{{%option_guest_vote}}');
        $this->dropIndex('idx_option_guest_vote_poll_id', '{{%option_guest_vote}}');
        $this->dropColumn('{{%option_guest_vote}}', 'guest_ip_key');
        $this->dropColumn('{{%option_guest_vote}}', 'poll_id');
    }
}
