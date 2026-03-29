<?php

use yii\db\Migration;

class m260329_120000_drop_guest_ip_unique_index_for_poll_comment extends Migration
{
    public function safeUp()
    {
        // Прибираємо жорстке обмеження "1 IP = 1 коментар" для гостьових коментарів.
        $indexExists = (new \yii\db\Query())
            ->from('information_schema.statistics')
            ->where([
                'table_schema' => $this->db->createCommand('SELECT DATABASE()')->queryScalar(),
                'table_name' => $this->db->schema->getRawTableName('{{%poll_comment}}'),
                'index_name' => 'ux_poll_comment_poll_guest_ip_key',
            ])
            ->exists($this->db);

        if ($indexExists) {
            $this->dropIndex('ux_poll_comment_poll_guest_ip_key', '{{%poll_comment}}');
        }
    }

    public function safeDown()
    {
        // Повертаємо індекс лише якщо його ще немає.
        $indexExists = (new \yii\db\Query())
            ->from('information_schema.statistics')
            ->where([
                'table_schema' => $this->db->createCommand('SELECT DATABASE()')->queryScalar(),
                'table_name' => $this->db->schema->getRawTableName('{{%poll_comment}}'),
                'index_name' => 'ux_poll_comment_poll_guest_ip_key',
            ])
            ->exists($this->db);

        if (!$indexExists) {
            $this->createIndex('ux_poll_comment_poll_guest_ip_key', '{{%poll_comment}}', ['poll_id', 'guest_ip_key'], true);
        }
    }
}
