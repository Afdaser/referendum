<?php

use yii\db\Migration;

class m230430_202122_create_table_user_data extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_data}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'login' => $this->string()->Comment('Login'),
            'name' => $this->string()->Comment('Name'),
            'lastname' => $this->string()->Comment('Lastname'),
            'password' => $this->string()->Comment('Password'),
            'city_id' => $this->integer()->unsigned()->Comment('City'),
            'region_id' => $this->integer()->unsigned()->Comment('Region'),
            'country_id' => $this->integer()->unsigned()->Comment('Country'),
            'date_birthday' => $this->date()->Comment('Date birthday'),
            'email' => $this->string()->notNull()->Comment('Email'),
            'phone' => $this->string(35)->Comment('Phone'),
            'sex' => $this->tinyInteger(1)->Comment('Sex'),
            'marital' => $this->tinyInteger(1)->Comment('Marital'),
            'preferences' => $this->string()->Comment('Preferences'),
            'is_active' => $this->tinyInteger(1)->notNull()->defaultValue('0')->Comment('Is active'),
            'identity' => $this->string()->Comment('Identity'),
            'network' => $this->string()->Comment('Network'),
            'state' => $this->tinyInteger(1)->Comment('State'),
            'date_add' => $this->dateTime()->notNull()->Comment('Date add'),
            'date_update' => $this->dateTime()->notNull()->Comment('Date update'),
            'is_admin' => $this->tinyInteger(1)->notNull()->defaultValue('0')->Comment('Is admin'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%user_data}}');
    }
}
