<?php

use yii\db\Migration;

class m230430_195250_create_table_region_city extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%region_city}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'region_id' => $this->integer()->unsigned()->notNull()->Comment('Region'),
            'country_id' => $this->integer()->unsigned()->notNull()->Comment('Country'),
            'name' => $this->string()->notNull()->Comment('Name'),
            'created_by' => $this->integer()->unsigned()->Comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->Comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->Comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->Comment('Updated at:'),
        ], $tableOptions);

        $this->createIndex('fk_region_city_country1_idx', '{{%region_city}}', 'country_id');
        $this->createIndex('fk_region_city_country_region1_idx', '{{%region_city}}', 'region_id');
        $this->addForeignKey('fk_region_city_country1', '{{%region_city}}', 'country_id', '{{%country}}', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_region_city_country_region1', '{{%region_city}}', 'region_id', '{{%country_region}}', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('{{%region_city}}');
    }
}
