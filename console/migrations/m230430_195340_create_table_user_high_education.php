<?php

use yii\db\Migration;

class m230430_195340_create_table_user_high_education extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_high_education}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'user_id' => $this->integer()->unsigned()->notNull()->Comment('User'),
            'country_id' => $this->integer()->unsigned()->Comment('Country'),
            'region_id' => $this->integer()->unsigned()->Comment('Region'),
            'city_id' => $this->integer()->unsigned()->Comment('City'),
            'university' => $this->string()->Comment('University'),
            'faculty' => $this->string()->Comment('Faculty'),
            'speciality' => $this->string()->Comment('Speciality'),
            'status' => $this->string()->Comment('Status'),
//            'year_begin' => $this->date()->Comment('Year begin'),
            'year_begin' => $this->smallInteger()->Comment('Year begin'),
//            'year_end' => $this->date()->Comment('Year end'),
            'year_end' => $this->smallInteger()->Comment('Year end'),
            'date_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->Comment('Date add'),
            'date_update' => $this->dateTime()->notNull()->Comment('Date update'),
            'created_by' => $this->integer()->unsigned()->Comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->Comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->Comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->Comment('Updated at:'),
        ], $tableOptions);

        $this->createIndex('city_id', '{{%user_high_education}}', 'city_id');
        $this->createIndex('country_id', '{{%user_high_education}}', 'country_id');
        $this->createIndex('user_id', '{{%user_high_education}}', 'user_id', true);
        $this->createIndex('region_id', '{{%user_high_education}}', 'region_id');
        $this->addForeignKey('user_high_education_ibfk_1', '{{%user_high_education}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('user_high_education_ibfk_2', '{{%user_high_education}}', 'country_id', '{{%country}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('user_high_education_ibfk_3', '{{%user_high_education}}', 'region_id', '{{%country_region}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('user_high_education_ibfk_4', '{{%user_high_education}}', 'city_id', '{{%region_city}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%user_high_education}}');
    }
}
