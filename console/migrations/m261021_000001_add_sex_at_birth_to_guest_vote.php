<?php

use yii\db\Migration;

class m261021_000001_add_sex_at_birth_to_guest_vote extends Migration
{
    public function safeUp()
    {
        // Matches User::SEX_MAN / User::SEX_WOMAN; NULL means unanswered or skipped.
        $this->addColumn('{{%option_guest_vote}}', 'sex_at_birth', $this->smallInteger()->null());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%option_guest_vote}}', 'sex_at_birth');
    }
}
