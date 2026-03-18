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

        // Ніяких видалень даних: якщо після повторного бекфілу лишились NULL, зупиняємо міграцію явною помилкою.
        $nullPollIdCount = (new \yii\db\Query())
            ->from('{{%option_guest_vote}}')
            ->where(['poll_id' => null])
            ->count('*', $this->db);
        if ((int) $nullPollIdCount > 0) {
            throw new \RuntimeException('Не вдалося заповнити poll_id для всіх option_guest_vote без втрати даних.');
        }

        $this->alterColumn('{{%option_guest_vote}}', 'poll_id', $this->integer()->unsigned()->notNull()->comment('Poll'));

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
