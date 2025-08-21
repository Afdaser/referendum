<?php

use yii\db\Migration;

class m230430_195159_create_table_poll extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%poll}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'title' => $this->string()->notNull()->Comment('Title'),
            'describe' => $this->text()->Comment('Describe'),
            'user_id' => $this->integer()->unsigned()->notNull()->Comment('User'),
            'rating' => $this->integer()->notNull()->defaultValue('0')->Comment('Rating'),
            'status' => $this->tinyInteger(4)->notNull()->Comment('Status'),
            'views' => $this->integer()->notNull()->defaultValue('0')->Comment('Views'),
            'result_type' => $this->tinyInteger(4)->notNull()->defaultValue('0')->Comment('Result type'),
            'poll_language_id' => $this->integer()->unsigned()->notNull()->defaultValue('1')->Comment('Language'),
            'show_for_all_languages' => $this->tinyInteger(1)->notNull()->defaultValue('0')->Comment('Show for all languages'),
            'poll_sex' => $this->tinyInteger(1)->notNull()->defaultValue('0')->Comment('Sex'),
            'poll_country_id' => $this->integer()->unsigned()->notNull()->defaultValue('1')->Comment('Country'),
            'poll_region_id' => $this->integer()->unsigned()->notNull()->defaultValue('1')->Comment('Region'),
            'poll_city_id' => $this->integer()->unsigned()->notNull()->defaultValue('1')->Comment('City'),
            'poll_min_age' => $this->integer()->notNull()->defaultValue('0')->Comment('Poll min age'),
            'poll_max_age' => $this->integer()->notNull()->defaultValue('100')->Comment('Poll max age'),
            'votes_count_close' => $this->integer()->notNull()->defaultValue('0')->Comment('Votes count close'),
            'date_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->Comment('Date add'),
            'date_update' => $this->timestamp()->Comment('Date update'),
            'show_on_slider' => $this->tinyInteger(4)->notNull()->defaultValue('0')->Comment('Show on slider'),
            'created_by' => $this->integer()->unsigned()->Comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->Comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->Comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->Comment('Updated at:'),
            
        ], $tableOptions);

        $this->createIndex('poll_language_id', '{{%poll}}', 'poll_language_id');
        $this->createIndex('poll_region_id', '{{%poll}}', 'poll_region_id');
        $this->createIndex('poll_country_id', '{{%poll}}', 'poll_country_id');
        $this->createIndex('poll_city_id', '{{%poll}}', 'poll_city_id');
        $this->createIndex('user_id', '{{%poll}}', 'user_id');
        $this->addForeignKey('polls_ibfk_2', '{{%poll}}', 'poll_language_id', '{{%language}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%poll}}');
    }
}
