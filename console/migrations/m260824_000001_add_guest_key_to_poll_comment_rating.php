<?php

use yii\db\Migration;

/**
 * Додає просту IP-ідентифікацію для гостьових оцінок коментарів.
 */
class m260824_000001_add_guest_key_to_poll_comment_rating extends Migration
{
    public function safeUp()
    {
        // Зареєстровані голоси й надалі визначаються user_id, а гостьові — guest_key.
        $this->alterColumn('{{%poll_comment_rating}}', 'user_id', $this->integer()->unsigned()->null()->comment('User'));
        $this->addColumn('{{%poll_comment_rating}}', 'guest_key', $this->string(67)->null()->after('user_id')->comment('Guest IP key'));
        $this->createIndex('uq_poll_comment_rating_comment_guest', '{{%poll_comment_rating}}', ['poll_comment_id', 'guest_key'], true);
    }

    public function safeDown()
    {
        $this->dropIndex('uq_poll_comment_rating_comment_guest', '{{%poll_comment_rating}}');
        $this->dropColumn('{{%poll_comment_rating}}', 'guest_key');
        $this->alterColumn('{{%poll_comment_rating}}', 'user_id', $this->integer()->unsigned()->notNull()->comment('User'));
    }
}
