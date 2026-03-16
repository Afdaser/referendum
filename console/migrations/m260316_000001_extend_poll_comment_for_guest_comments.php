<?php

use yii\db\Migration;

class m260316_000001_extend_poll_comment_for_guest_comments extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%poll_comment}}', 'is_guest', $this->tinyInteger(1)->notNull()->defaultValue(0)->after('user_id')->comment('Is guest comment'));
        $this->addColumn('{{%poll_comment}}', 'guest_nickname', $this->string(60)->null()->after('is_guest')->comment('Guest nickname'));
        $this->addColumn('{{%poll_comment}}', 'user_ip', $this->bigInteger()->unsigned()->null()->after('guest_nickname')->comment('User IP'));
        $this->addColumn('{{%poll_comment}}', 'ip_of_user', $this->string(67)->null()->after('user_ip')->comment('User IP text'));

        // Для гостьових коментарів user_id має бути nullable.
        $this->alterColumn('{{%poll_comment}}', 'user_id', $this->integer()->unsigned()->null()->comment('User'));

        // Індекс прискорює перевірку "1 IP = 1 гостьовий коментар у межах одного опитування".
        $this->createIndex('idx_poll_comment_poll_guest_ip', '{{%poll_comment}}', ['poll_id', 'is_guest', 'user_ip']);
    }

    public function safeDown()
    {
        $this->dropIndex('idx_poll_comment_poll_guest_ip', '{{%poll_comment}}');
        $this->dropColumn('{{%poll_comment}}', 'ip_of_user');
        $this->dropColumn('{{%poll_comment}}', 'user_ip');
        $this->dropColumn('{{%poll_comment}}', 'guest_nickname');
        $this->dropColumn('{{%poll_comment}}', 'is_guest');

        $this->alterColumn('{{%poll_comment}}', 'user_id', $this->integer()->unsigned()->notNull()->comment('User'));
    }
}

