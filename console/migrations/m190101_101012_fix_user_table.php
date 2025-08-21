<?php
class m190101_101012_fix_user_table extends \yii\db\Migration
{
    public function up()
    {
           $this->execute('ALTER TABLE {{%user}} ADD COLUMN `status` SMALLINT(6) NOT NULL DEFAULT "10" AFTER `email`');
    }

    public function down()
    {

    }
}