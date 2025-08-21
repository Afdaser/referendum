<?php

use yii\db\Migration;

class m230430_195309_create_table_poll_tag extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%poll_tag}}', [
            'id' => $this->primaryKey()->unsigned()->Comment('ID'),
            'poll_id' => $this->integer()->unsigned()->notNull()->Comment('Poll'),
            'tag_id' => $this->integer()->unsigned()->notNull()->Comment('Tag'),
//            "PRIMARY KEY (`poll_id`, `tag_id`)",
            "UNIQUE (`poll_id`, `tag_id`)",
        ], $tableOptions);

// ALTER TABLE `yii_referendum_db06`.`poll_tag` ADD UNIQUE `unique_poll_to_tag` (`poll_id`, `tag_id`);

        $this->createIndex('tag_id', '{{%poll_tag}}', 'tag_id');
//        $this->createIndex('poll_id', '{{%poll_tag}}', 'poll_id');
        $this->addForeignKey('poll_tags_ibfk_1', '{{%poll_tag}}', 'poll_id', '{{%poll}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('poll_tags_ibfk_2', '{{%poll_tag}}', 'tag_id', '{{%tag}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%poll_tag}}');
    }
}
