<?php

use common\models\OptionGuestVote;
use common\models\Poll;
use common\models\PollOption;
use common\models\User;
use frontend\modules\poll\controllers\PollController;
use PHPUnit\Framework\TestCase;
use yii\db\Connection;
use yii\web\Application;
use yii\web\Session;

/** Exercise the real controller and persistence against an isolated database. */
class GuestSampleTest extends TestCase
{
    private $controller;
    private $sessionKey;

    protected function setUp(): void
    {
        Yii::setAlias('@common', dirname(__DIR__) . '/common');
        Yii::setAlias('@frontend', dirname(__DIR__) . '/frontend');
        $this->sessionKey = PollController::GUEST_SAMPLE_SESSION_PREFIX . '1';
        $_SESSION = [];
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REMOTE_ADDR'] = '192.0.2.10';
        new Application([
            'id' => 'guest-sample-test',
            'basePath' => dirname(__DIR__),
            'vendorPath' => dirname(__DIR__) . '/vendor',
            'language' => 'en-US',
            'components' => [
                'db' => ['class' => Connection::class, 'dsn' => 'sqlite::memory:'],
                'request' => ['cookieValidationKey' => 'test', 'scriptUrl' => '/index.php', 'hostInfo' => 'http://localhost'],
                'session' => ['class' => GuestSampleMemorySession::class],
                'user' => ['identityClass' => User::class, 'enableSession' => false],
                'i18n' => ['translations' => ['poll' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@common/messages', 'forceTranslation' => true,
                ]]],
            ],
        ]);
        $db = Yii::$app->db;
        $db->createCommand()->createTable('language', ['id' => 'pk', 'locale' => 'string'])->execute();
        $db->createCommand()->createTable('poll', ['id' => 'pk', 'poll_language_id' => 'integer'])->execute();
        $db->createCommand()->createTable('poll_option', ['id' => 'pk', 'poll_id' => 'integer'])->execute();
        $db->createCommand()->createTable('tag', ['id' => 'pk', 'name' => 'string'])->execute();
        $db->createCommand()->createTable('poll_tag', ['poll_id' => 'integer', 'tag_id' => 'integer'])->execute();
        $db->createCommand()->createTable('option_guest_vote', [
            'id' => 'pk', 'option_id' => 'integer', 'poll_id' => 'integer',
            'user_ip' => 'bigint', 'ip_of_user' => 'string', 'guest_ip_key' => 'string',
            'date_add' => 'datetime', 'sex_at_birth' => 'integer', 'age_interval' => 'integer',
            'created_by' => 'integer', 'updated_by' => 'integer',
            'created_at' => 'integer', 'updated_at' => 'integer',
        ])->execute();
        require_once dirname(__DIR__) . '/console/migrations/m261026_000001_add_location_to_guest_vote.php';
        $migration = new m261026_000001_add_location_to_guest_vote(['db' => $db, 'compact' => true]);
        ob_start();
        $migration->safeUp();
        ob_end_clean();
        $db->createCommand()->insert('language', ['id' => 1, 'locale' => 'en-US'])->execute();
        $db->createCommand()->insert('poll', ['id' => 1, 'poll_language_id' => 1])->execute();
        $db->createCommand()->insert('poll_option', ['id' => 1, 'poll_id' => 1])->execute();
        $this->controller = new PollController('poll', Yii::$app);
        Yii::$app->controller = $this->controller;
    }

    protected function tearDown(): void
    {
        Yii::$app->db->close();
        Yii::$app = null;
        $_SESSION = [];
        unset($_SERVER['REQUEST_METHOD'], $_SERVER['REMOTE_ADDR']);
        parent::tearDown();
    }

    private function vote(): OptionGuestVote
    {
        $option = PollOption::findOne(1);
        $this->assertTrue($option->voteByGuest());
        Yii::$app->session->set($this->sessionKey, $option->createdGuestVoteId);
        return OptionGuestVote::findOne($option->createdGuestVoteId);
    }

    private function submit(array $data): void
    {
        Yii::$app->request->setBodyParams($data);
        $this->controller->actionGuestSample(1);
    }

    private function advanceToState(): OptionGuestVote
    {
        $vote = $this->vote();
        $this->submit(['step' => '1', 'sex_at_birth' => (string) User::SEX_MAN]);
        $this->submit(['step' => '2', 'age_interval' => '2']);
        $this->assertSame(3, Yii::$app->session->get($this->sessionKey . ':step'));
        return $vote;
    }

    public function testUsFlowSavesLocationWithoutChangingOrDuplicatingVote(): void
    {
        $vote = $this->advanceToState();
        $this->assertSame('US', $vote->country_code);
        $this->assertNull($vote->state_code);
        $this->submit(['step' => '3', 'state_code' => 'CA', 'country_code' => 'NZ', 'option_id' => '9']);
        $vote->refresh();
        $this->assertSame('US', $vote->country_code);
        $this->assertSame('CA', $vote->state_code);
        $this->assertEquals(1, $vote->option_id);
        $this->assertEquals(User::SEX_MAN, $vote->sex_at_birth);
        $this->assertEquals(2, $vote->age_interval);
        $this->assertFalse(Yii::$app->session->has($this->sessionKey));
        $this->submit(['step' => '3', 'state_code' => 'NY']);
        $vote->refresh();
        $this->assertSame('CA', $vote->state_code);
        $this->assertEquals(1, OptionGuestVote::find()->count());
    }

    public function testInvalidAndOutOfOrderSubmissionsDoNotAdvance(): void
    {
        $vote = $this->vote();
        $this->submit(['step' => '3', 'state_code' => 'CA']);
        $vote->refresh();
        $this->assertNull($vote->state_code);
        $this->submit(['step' => '1', 'sex_at_birth' => (string) User::SEX_WOMAN]);
        $this->submit(['step' => '2', 'age_interval' => '6']);
        foreach (['', 'XX', 'ca', ['CA']] as $invalid) {
            $this->submit(['step' => '3', 'state_code' => $invalid]);
            $this->assertSame(3, Yii::$app->session->get($this->sessionKey . ':step'));
            $this->assertTrue(Yii::$app->session->hasFlash('guestSampleError'));
            $vote->refresh();
            $this->assertNull($vote->state_code);
        }
        $this->submit(['step' => '2', 'age_interval' => '1']);
        $vote->refresh();
        $this->assertEquals(6, $vote->age_interval);
        $this->submit(['step' => '3', 'state_code' => 'DC']);
        $vote->refresh();
        $this->assertSame('DC', $vote->state_code);
        $this->assertFalse(Yii::$app->session->hasFlash('guestSampleError'));
    }

    public function testSkipPreservesEarlierAnswersAndInferredCountry(): void
    {
        $vote = $this->advanceToState();
        $this->submit(['step' => '3', 'skip' => '1', 'state_code' => '']);
        $vote->refresh();
        $this->assertNull($vote->state_code);
        $this->assertSame('US', $vote->country_code);
        $this->assertEquals(2, $vote->age_interval);
        $this->assertFalse(Yii::$app->session->has($this->sessionKey));
        $this->assertFalse(Yii::$app->session->has($this->sessionKey . ':step'));
    }

    public function testOtherEditionsFinishAfterAge(): void
    {
        Yii::$app->db->createCommand()->update('language', ['locale' => 'en-NZ'], ['id' => 1])->execute();
        $vote = $this->vote();
        $this->assertNull($vote->country_code);
        $this->assertSame(2, $vote->sampleStepCount);
        $this->submit(['step' => '1', 'sex_at_birth' => (string) User::SEX_MAN]);
        $this->submit(['step' => '2', 'age_interval' => '1']);
        $this->assertFalse(Yii::$app->session->has($this->sessionKey));
        $this->submit(['step' => '3', 'state_code' => 'CA']);
        $vote->refresh();
        $this->assertNull($vote->state_code);
    }

    public function testDifferentIpCannotUpdatePendingVote(): void
    {
        $vote = $this->advanceToState();
        $_SERVER['REMOTE_ADDR'] = '192.0.2.11';
        Yii::$app->set('request', [
            'class' => yii\web\Request::class,
            'cookieValidationKey' => 'test', 'scriptUrl' => '/index.php', 'hostInfo' => 'http://localhost',
        ]);
        $this->submit(['step' => '3', 'state_code' => 'CA']);
        $vote->refresh();
        $this->assertNull($vote->state_code);
        $this->assertFalse(Yii::$app->session->has($this->sessionKey));
    }

    public function testStateFormHasAllChoicesNoDefaultAndAccessibleSkip(): void
    {
        $html = Yii::$app->view->renderFile(
            Yii::getAlias('@frontend/modules/poll/views/poll/guest-sample.php'),
            ['poll' => Poll::findOne(1), 'step' => 3, 'totalSteps' => 3]
        );
        $this->assertStringContainsString('Step 3 of 3', $html);
        $this->assertStringContainsString('Country: United States', $html);
        $this->assertStringContainsString('for="guest-state-1"', $html);
        $this->assertStringContainsString('name="state_code"', $html);
        $this->assertStringContainsString('formnovalidate', $html);
        $this->assertStringContainsString('name="_csrf"', $html);
        $this->assertStringNotContainsString('selected=', $html);
        $this->assertSame(52, substr_count($html, '<option '));
        $this->assertLessThan(strpos($html, '>Wyoming<'), strpos($html, '>Alabama<'));
    }
}

class GuestSampleMemorySession extends Session
{
    public function open() {}
    public function close() {}
    public function getIsActive() { return true; }
}
