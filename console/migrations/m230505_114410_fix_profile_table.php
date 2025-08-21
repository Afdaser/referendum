<?php

use yii\db\Migration;

/**
 * Class m230315_114410_fix_profile_table
 */
class m230505_114410_fix_profile_table extends Migration {

    protected $table_id = 'profile';
    protected $table_name = '{{%profile}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        // user_id
        // name
        $this->addColumn($this->table_name, 'lastname', $this->string(128)->Comment('Lastname')->after('name'));
        $this->addColumn($this->table_name, 'city_id', $this->integer()->unsigned()->Comment('City')->after('lastname'));
        $this->addColumn($this->table_name, 'region_id', $this->integer()->unsigned()->Comment('Region')->after('city_id'));
        $this->addColumn($this->table_name, 'country_id', $this->integer()->unsigned()->Comment('Country')->after('region_id'));
        $this->addColumn($this->table_name, 'date_birthday', $this->date()->Comment('Date birthday')->after('country_id'));
        // public_email ~~ email
        $this->addColumn($this->table_name, 'phone', $this->string(128)->Comment('Phone')->after('public_email'));
        $this->addColumn($this->table_name, 'gender', $this->tinyInteger()->unsigned()->Comment('Gender')->after('phone'));
        $this->addColumn($this->table_name, 'marital', $this->tinyInteger()->unsigned()->Comment('Marital')->after('gender'));
        $this->addColumn($this->table_name, 'preferences', $this->string()->Comment('Preferences')->after('marital'));
        $this->addColumn($this->table_name, 'is_active', $this->tinyInteger()->defaultValue(0)->Comment('Is active')->after('preferences'));
        $this->addColumn($this->table_name, 'identity', $this->string()->Comment('Identity')->after('is_active'));
        $this->addColumn($this->table_name, 'network', $this->string()->Comment('Network')->after('identity'));
        $this->addColumn($this->table_name, 'state', $this->tinyInteger()->unsigned()->Comment('State')->after('network'));
        $this->addColumn($this->table_name, 'date_add', $this->dateTime()->Comment('Date add')->after('state'));
        $this->addColumn($this->table_name, 'date_update', $this->dateTime()->Comment('Date update')->after('date_add'));
        $this->addColumn($this->table_name, 'is_admin', $this->tinyInteger()->defaultValue(0)->unsigned()->Comment('Is admin')->after('date_update'));
        // gravatar_email
        // gravatar_id
//location
//website
//bio
//timezone
        $this->addColumn($this->table_name, 'created_by', $this->integer()->unsigned()->Comment('Created by:'));
        $this->addColumn($this->table_name, 'updated_by', $this->integer()->unsigned()->Comment('Updated by:'));
        $this->addColumn($this->table_name, 'created_at', $this->integer()->unsigned()->Comment('Created at:'));
        $this->addColumn($this->table_name, 'updated_at', $this->integer()->unsigned()->Comment('Updated at:'));

        $this->createIndex('city_id', '{{%profile}}', 'city_id');
        $this->createIndex('region_id', '{{%profile}}', 'region_id');
        $this->createIndex('country_id', '{{%profile}}', 'country_id');

        $this->addForeignKey('profile_ibfk_4', '{{%profile}}', 'city_id', '{{%region_city}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('profile_ibfk_3', '{{%profile}}', 'region_id', '{{%country_region}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('profile_ibfk_2', '{{%profile}}', 'country_id', '{{%country}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
//        echo "m230315_114410_fix_profile_table cannot be reverted.\n";
//        return false;
        $this->dropForeignKey('profile_ibfk_4', '{{%profile}}');
        $this->dropForeignKey('profile_ibfk_3', '{{%profile}}');
        $this->dropForeignKey('profile_ibfk_2', '{{%profile}}');

        $this->dropIndex('city_id', '{{%profile}}', 'city_id');
        $this->dropIndex('region_id', '{{%profile}}', 'region_id');
        $this->dropIndex('country_id', '{{%profile}}', 'country_id');

        $this->dropColumn($this->table_name, 'lastname');
        $this->dropColumn($this->table_name, 'city_id');
        $this->dropColumn($this->table_name, 'region_id');
        $this->dropColumn($this->table_name, 'country_id');
        $this->dropColumn($this->table_name, 'date_birthday');
        // public_email ~~ email
        $this->dropColumn($this->table_name, 'phone');
        $this->dropColumn($this->table_name, 'gender');
        $this->dropColumn($this->table_name, 'marital');
        $this->dropColumn($this->table_name, 'preferences');
        $this->dropColumn($this->table_name, 'is_active');
        $this->dropColumn($this->table_name, 'identity');
        $this->dropColumn($this->table_name, 'network');
        $this->dropColumn($this->table_name, 'state');
        $this->dropColumn($this->table_name, 'date_add');
        $this->dropColumn($this->table_name, 'date_update');
        $this->dropColumn($this->table_name, 'is_admin');

        $this->dropColumn($this->table_name, 'created_by');
        $this->dropColumn($this->table_name, 'updated_by');
        $this->dropColumn($this->table_name, 'created_at');
        $this->dropColumn($this->table_name, 'updated_at');
    }

}
