<?php

use yii\db\Migration;

/**
 * Дозволяє гостям оцінювати коментарі та гарантує один поточний голос з IP.
 */
class m260824_000001_allow_guest_poll_comment_rating extends Migration
{
    public function safeUp()
    {
        $this->alterColumn(
            '{{%poll_comment_rating}}',
            'user_id',
            $this->integer()->unsigned()->null()->comment('User')
        );
        $this->addColumn(
            '{{%poll_comment_rating}}',
            'guest_ip_key',
            $this->string(67)->null()->after('user_id')->comment('Guest IP key')
        );
        $this->createIndex(
            'ux_poll_comment_rating_comment_guest_ip_key',
            '{{%poll_comment_rating}}',
            ['poll_comment_id', 'guest_ip_key'],
            true
        );
    }

    public function safeDown()
    {
        // Гостьові записи не мають user_id, тому перед поверненням NOT NULL їх прибираємо.
        $this->delete('{{%poll_comment_rating}}', ['user_id' => null]);
        $this->dropIndex(
            'ux_poll_comment_rating_comment_guest_ip_key',
            '{{%poll_comment_rating}}'
        );
        $this->dropColumn('{{%poll_comment_rating}}', 'guest_ip_key');
        $this->alterColumn(
            '{{%poll_comment_rating}}',
            'user_id',
            $this->integer()->unsigned()->notNull()->comment('User')
        );
    }
}
