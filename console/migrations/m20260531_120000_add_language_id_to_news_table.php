<?php

use yii\db\Migration;

class m20260531_120000_add_language_id_to_news_table extends Migration
{
    public function safeUp()
    {
        // Не нашкодити: наявні новини залишаємо на базовому українському піддомені.
        $this->addColumn('{{%news}}', 'language_id', $this->integer()->unsigned()->notNull()->defaultValue(1)->after('slug')->comment('Language'));

        if ($this->indexExists('{{%news}}', 'ux_news_slug')) {
            $this->dropIndex('ux_news_slug', '{{%news}}');
        }

        $this->createIndex('idx_news_language_id', '{{%news}}', 'language_id');
        $this->createIndex('ux_news_slug_language_id', '{{%news}}', ['slug', 'language_id'], true);
        $this->addForeignKey('fk_news_language_id', '{{%news}}', 'language_id', '{{%language}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_news_language_id', '{{%news}}');
        $this->dropIndex('ux_news_slug_language_id', '{{%news}}');
        $this->dropIndex('idx_news_language_id', '{{%news}}');
        $this->dropColumn('{{%news}}', 'language_id');

        // Повертаємо попереднє обмеження, якщо відкат справді потрібен.
        $this->createIndex('ux_news_slug', '{{%news}}', 'slug', true);
    }

    private function indexExists(string $tableName, string $indexName): bool
    {
        if ($this->db->driverName !== 'mysql') {
            // Для неміграційних тестових драйверів не робимо зайвих припущень про службові таблиці.
            return true;
        }

        $rawTableName = $this->db->schema->getRawTableName($tableName);

        return (bool) (new \yii\db\Query())
            ->from('information_schema.STATISTICS')
            ->where([
                'TABLE_SCHEMA' => new \yii\db\Expression('DATABASE()'),
                'TABLE_NAME' => $rawTableName,
                'INDEX_NAME' => $indexName,
            ])
            ->exists($this->db);
    }
}
