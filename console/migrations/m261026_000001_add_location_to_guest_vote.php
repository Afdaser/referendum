<?php

use yii\db\Migration;

class m261026_000001_add_location_to_guest_vote extends Migration
{
    public function safeUp()
    {
        // Location remains unknown until the guest confirms a US state.
        $this->addColumn('{{%option_guest_vote}}', 'country_code', $this->string(2)->null());
        $this->addColumn('{{%option_guest_vote}}', 'state_code', $this->string(2)->null());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%option_guest_vote}}', 'state_code');
        $this->dropColumn('{{%option_guest_vote}}', 'country_code');
    }
}
