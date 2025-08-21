<?php

use yii\db\Migration;

class m230430_195043_create_table_country extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%country}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'name' => $this->string()->notNull()->Comment('Name'),
            'sorting_uk' => $this->smallInteger()->notNull()->defaultValue('0'),
            'sorting_ru' => $this->smallInteger()->notNull()->defaultValue('0'),
            'sorting_en' => $this->smallInteger()->notNull()->defaultValue('0'),
            'sorting_no' => $this->smallInteger()->notNull()->defaultValue('0'),

            'created_by' => $this->integer()->unsigned()->Comment('Created by:'),
            'updated_by' => $this->integer()->unsigned()->Comment('Updated by:'),
            'created_at' => $this->integer()->unsigned()->Comment('Created at:'),
            'updated_at' => $this->integer()->unsigned()->Comment('Updated at:'),

        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%country}}');
    }
}
