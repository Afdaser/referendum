<?php

use yii\db\Migration;

class m261022_000001_add_age_interval_to_guest_vote extends Migration
{
    public function safeUp()
    {
        // NULL preserves unknown ages for existing and skipped votes.
        $this->addColumn('{{%option_guest_vote}}', 'age_interval', $this->smallInteger()->null());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%option_guest_vote}}', 'age_interval');
    }
}
