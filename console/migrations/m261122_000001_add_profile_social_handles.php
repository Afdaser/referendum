<?php

use yii\db\Migration;

/** Додає імена акаунтів для трьох соцмереж до профілю. */
class m261122_000001_add_profile_social_handles extends Migration
{
    public function up()
    {
        $this->addColumn('{{%profile}}', 'social_facebook', $this->string(60)->null());
        $this->addColumn('{{%profile}}', 'social_instagram', $this->string(60)->null());
        $this->addColumn('{{%profile}}', 'social_reddit', $this->string(60)->null());
    }

    public function down()
    {
        echo "Імена акаунтів уже можуть використовуватися в профілях; міграцію не відкочують.\n";
        return false;
    }
}
