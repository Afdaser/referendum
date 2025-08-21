<?php

use yii\db\Migration;

class m230430_195242_create_table_country_region extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%country_region}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'country_id' => $this->integer()->unsigned()->notNull()->Comment('Country'),
            'name' => $this->string()->notNull()->Comment('Name'),
            'created_by' => $this->integer()->unsigned()->Comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->Comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->Comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->Comment('Updated at:'),
        ], $tableOptions);

        $this->createIndex('country_id', '{{%country_region}}', 'country_id');
        $this->addForeignKey('country_regions_ibfk_1', '{{%country_region}}', 'country_id', '{{%country}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%country_region}}');
    }
}
