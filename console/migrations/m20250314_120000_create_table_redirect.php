<?php

use yii\db\Migration;

class m20250314_120000_create_table_redirect extends Migration
{
    public function up()
    {
        // Створюємо таблицю редіректів для адмінки.
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%redirect}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'subdomain' => $this->string(128)->notNull()->defaultValue('')->Comment('Subdomain'),
            'from_path' => $this->string(255)->notNull()->Comment('From path'),
            'to_url' => $this->string(255)->notNull()->Comment('To url'),
            'created_by' => $this->integer()->unsigned()->Comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->Comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->Comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->Comment('Updated at:'),
        ], $tableOptions);

        $this->createIndex('idx_redirect_subdomain_from_path', '{{%redirect}}', ['subdomain', 'from_path'], true);
    }

    public function down()
    {
        $this->dropTable('{{%redirect}}');
    }
}
