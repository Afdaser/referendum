<?php

use console\controllers\StatisticsController;
use PHPUnit\Framework\TestCase;
use yii\console\Application;
use yii\db\Connection;
use yii\web\View;

class StatisticsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Yii::setAlias('@common', dirname(__DIR__) . '/common');
        Yii::setAlias('@console', dirname(__DIR__) . '/console');
        new Application([
            'id' => 'statistics-test',
            'basePath' => dirname(__DIR__),
            'language' => 'en-US',
            'sourceLanguage' => 'uk-UA',
            'modules' => ['user' => ['class' => dektrium\user\Module::class]],
            'components' => [
                'db' => ['class' => Connection::class, 'dsn' => 'sqlite::memory:'],
                'i18n' => ['translations' => ['*' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@common/messages',
                ]]],
            ],
        ]);
    }

    protected function tearDown(): void
    {
        Yii::$app->db->close();
        Yii::$app = null;
        parent::tearDown();
    }

    public function testCommentsRespectPublicationLanguageAndDailyBoundaries(): void
    {
        $db = Yii::$app->db;
        foreach ([
            'user' => 'id INTEGER, created_at INTEGER',
            'user_language' => 'user_id INTEGER, language_id INTEGER',
            'poll' => 'id INTEGER, poll_language_id INTEGER, date_add TEXT',
            'poll_option' => 'id INTEGER, poll_id INTEGER',
            'option_vote' => 'option_id INTEGER, date_add TEXT',
            'option_guest_vote' => 'option_id INTEGER, date_add TEXT',
            'poll_comment' => 'id INTEGER, poll_id INTEGER, parent_id INTEGER, user_id INTEGER, is_guest INTEGER, status INTEGER, date_add TEXT',
        ] as $table => $columns) {
            $db->createCommand("CREATE TABLE \"$table\" ($columns)")->execute();
        }
        $updatedAt = strtotime('2026-10-16 12:00:00');
        $date = static fn(int $offset): string => date('Y-m-d H:i:s', $updatedAt + $offset);
        $db->createCommand()->batchInsert('poll', ['id', 'poll_language_id', 'date_add'], [
            [1, 1, $date(-100000)], [2, 2, $date(-100000)],
        ])->execute();
        $db->createCommand()->batchInsert('poll_comment',
            ['id', 'poll_id', 'parent_id', 'user_id', 'is_guest', 'status', 'date_add'], [
                [1, 1, null, 1, 0, 1, $date(-86401)], // Older published comment.
                [2, 1, null, null, 1, 1, $date(-86400)], // Guest at inclusive start.
                [3, 1, 1, 2, 0, 1, $date(-1)], // Registered reply.
                [4, 1, null, 1, 0, 0, $date(-1)], // Unpublished: excluded entirely.
                [5, 2, null, null, 1, 1, $date(-100)], // Another language.
                [6, 1, null, 1, 0, 1, $date(0)], // Exclusive end of daily window.
                [7, 1, null, 1, 0, 1, $date(1)], // Outside daily window.
            ])->execute();

        $controller = new StatisticsController('statistics', Yii::$app);
        $method = new ReflectionMethod($controller, 'buildSnapshot');
        $method->setAccessible(true);
        foreach ([[null, 6, 3], [1, 5, 2], [2, 1, 1], [3, 0, 0]] as [$language, $total, $daily]) {
            $snapshot = $method->invoke($controller, $language, $updatedAt);
            $this->assertSame($total, $snapshot['comments']);
            $this->assertSame($daily, $snapshot['daily']['comments']);
        }
    }

    public function testCommentsCardSupportsOldCacheAndHidesZeroIncrement(): void
    {
        foreach ([[], ['comments' => 12, 'daily' => ['comments' => 0]],
            ['comments' => 12, 'daily' => ['comments' => 3]]] as $values) {
            $html = (new View())->renderFile(
                dirname(__DIR__) . '/frontend/modules/poll/views/site/_statistics.php',
                ['statistics' => $values + ['users' => 336, 'polls' => 160, 'votes' => 36256]]
            );
            $this->assertSame(4, substr_count($html, '<article '));
            $this->assertStringContainsString('landing-statistics__card--comments', $html);
            $this->assertStringContainsString('>Comments</span>', $html);
            $this->assertStringNotContainsString('>+0<', $html);
            $this->assertSame(isset($values['daily']) && $values['daily']['comments'] > 0,
                strpos($html, '>+3<') !== false);
        }
    }
}
