<?php

use yii\db\Migration;

class m260329_000001_drop_unique_index_poll_comment_guest_ip_key extends Migration
{
    public function safeUp()
    {
        // Прибираємо жорстке обмеження "один IP = один коментар в межах poll".
        $this->dropIndex('ux_poll_comment_poll_guest_ip_key', '{{%poll_comment}}');

        // Повертаємо звичайний (неунікальний) індекс для швидких вибірок за poll/ip.
        $this->createIndex('ix_poll_comment_poll_guest_ip_key', '{{%poll_comment}}', ['poll_id', 'guest_ip_key'], false);
    }

    public function safeDown()
    {
        // Відкат: повертаємо попередню унікальну поведінку.
        $this->dropIndex('ix_poll_comment_poll_guest_ip_key', '{{%poll_comment}}');
        $this->createIndex('ux_poll_comment_poll_guest_ip_key', '{{%poll_comment}}', ['poll_id', 'guest_ip_key'], true);
    }
}

