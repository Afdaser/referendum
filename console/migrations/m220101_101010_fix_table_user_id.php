<?php

class m220101_101010_fix_table_user_id extends \yii\db\Migration {

    protected $table_name = '{{%user}}';
    protected $table_id = 'user';
    protected $pk_in_table = 'id';
    protected $pk_in_parent = 'id';
    protected $child_table_id = 'profile';
    protected $child_table_name = '{{%profile}}';
    protected $fk_in_child = 'user_id';

    public function up() {

        $this->child_table_id = 'profile';
        $this->child_table_name = '{{%profile}}';
        $this->dropForeignKey("fk_{$this->table_id}_{$this->child_table_id}", $this->child_table_name);
        //$this->dropIndex("fk_{$this->table_id}_{$this->child_table_id}", $this->child_table_name);

        $this->child_table_id = 'token';
        $this->child_table_name = '{{%token}}';
        $this->dropForeignKey("fk_{$this->table_id}_{$this->child_table_id}", $this->child_table_name);
        //$this->dropIndex("fk_{$this->table_id}_{$this->child_table_id}", $this->child_table_name);

        $this->child_table_id = 'social_account';
        $this->child_table_id = 'account'; // FIX for compatibility
        $this->child_table_name = '{{%social_account}}';
        $this->dropForeignKey("fk_{$this->table_id}_{$this->child_table_id}", $this->child_table_name);
        $this->dropIndex("fk_{$this->table_id}_{$this->child_table_id}", $this->child_table_name);

        $this->execute("ALTER TABLE {$this->table_name} CHANGE `{$this->pk_in_table}` `{$this->pk_in_table}` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ");

        $this->child_table_id = 'profile';
        $this->child_table_name = '{{%profile}}';
        $this->execute("ALTER TABLE {$this->child_table_name} CHANGE `{$this->fk_in_child}` `{$this->fk_in_child}` INT(11) UNSIGNED NOT NULL ");
        $this->addForeignKey("fk_{$this->table_id}_{$this->child_table_id}", $this->child_table_name, $this->fk_in_child, $this->table_name, $this->pk_in_parent, 'CASCADE', 'RESTRICT');

        $this->child_table_id = 'token';
        $this->child_table_name = '{{%token}}';
        $this->execute("ALTER TABLE {$this->child_table_name} CHANGE `{$this->fk_in_child}` `{$this->fk_in_child}` INT(11) UNSIGNED NOT NULL ");
        $this->addForeignKey("fk_{$this->table_id}_{$this->child_table_id}", $this->child_table_name, $this->fk_in_child, $this->table_name, $this->pk_in_parent, 'CASCADE', 'RESTRICT');

        $this->child_table_id = 'social_account';
        $this->child_table_id = 'account'; // FIX for compatibility
        $this->child_table_name = '{{%social_account}}';
        $this->execute("ALTER TABLE {$this->child_table_name} CHANGE `{$this->fk_in_child}` `{$this->fk_in_child}` INT(11) UNSIGNED NOT NULL ");
        $this->addForeignKey("fk_{$this->table_id}_{$this->child_table_id}", $this->child_table_name, $this->fk_in_child, $this->table_name, $this->pk_in_parent, 'CASCADE', 'RESTRICT');

        $this->child_table_id = 'auth_assignment';
        $this->child_table_name = '{{%auth_assignment}}';
        $this->execute("ALTER TABLE {$this->child_table_name} CHANGE `{$this->fk_in_child}` `{$this->fk_in_child}` INT(11) UNSIGNED NOT NULL ");
        $this->addForeignKey("fk_{$this->table_id}_{$this->child_table_id}", $this->child_table_name, $this->fk_in_child, $this->table_name, $this->pk_in_parent, 'CASCADE', 'RESTRICT');
    }

    public function down() {

    }

}
