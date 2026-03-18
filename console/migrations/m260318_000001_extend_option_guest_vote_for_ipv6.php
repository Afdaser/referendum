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

        // На випадок історичних дублікатів залишаємо найстаріший гостьовий голос у межах одного poll + IP.
        $this->execute(
            "DELETE ogv1 FROM {{%option_guest_vote}} ogv1
             INNER JOIN {{%option_guest_vote}} ogv2
               ON ogv1.poll_id = ogv2.poll_id
              AND ogv1.guest_ip_key = ogv2.guest_ip_key
              AND ogv1.id > ogv2.id
             WHERE ogv1.guest_ip_key IS NOT NULL
               AND ogv1.guest_ip_key <> ''"
        );

        // Валідація даних перед введенням NOT NULL та індексів.
        $this->execute("DELETE FROM {{%option_guest_vote}} WHERE poll_id IS NULL");

        $this->alterColumn('{{%option_guest_vote}}', 'poll_id', $this->integer()->unsigned()->notNull()->comment('Poll'));

        $this->createIndex('idx_option_guest_vote_poll_id', '{{%option_guest_vote}}', 'poll_id');
        $this->addForeignKey('fk_option_guest_vote_poll', '{{%option_guest_vote}}', 'poll_id', '{{%poll}}', 'id', 'CASCADE', 'CASCADE');

        // Гарантія на рівні БД: один гостьовий голос з одного IP на одне опитування.
        $this->createIndex('ux_option_guest_vote_poll_guest_ip_key', '{{%option_guest_vote}}', ['poll_id', 'guest_ip_key'], true);
    }

    public function safeDown()
    {
        $this->dropIndex('ux_option_guest_vote_poll_guest_ip_key', '{{%option_guest_vote}}');
        $this->dropForeignKey('fk_option_guest_vote_poll', '{{%option_guest_vote}}');
        $this->dropIndex('idx_option_guest_vote_poll_id', '{{%option_guest_vote}}');
        $this->dropColumn('{{%option_guest_vote}}', 'guest_ip_key');
        $this->dropColumn('{{%option_guest_vote}}', 'poll_id');
    }
}
