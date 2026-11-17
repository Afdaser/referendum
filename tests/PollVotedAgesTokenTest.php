<?php

use common\models\Poll;
use PHPUnit\Framework\TestCase;
use yii\console\Application;
use yii\i18n\PhpMessageSource;

Yii::setAlias('@common', dirname(__DIR__) . '/common');

class PollVotedAgesTokenTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        new Application([
            'id' => 'voted-ages-test',
            'basePath' => dirname(__DIR__),
            'sourceLanguage' => 'uk-UA',
            'components' => [
                'i18n' => ['translations' => [
                    'poll' => ['class' => PhpMessageSource::class, 'basePath' => '@common/messages'],
                ]],
            ],
        ]);
    }

    public function testStaticTextShowsOnlyVotedAgeGroups(): void
    {
        $poll = new PollWithVotedAgesToken();
        $poll->votedAges = [2 => '25–34', 4 => '45–54'];

        $this->assertSame('Ages: 25–34, 45–54', $this->render($poll, 'Ages: @voted_ages'));
    }

    public function testMissingAgeDataIsTranslated(): void
    {
        $poll = new PollWithVotedAgesToken();
        foreach ([
            'uk-UA' => 'Недостатньо даних',
            'en-US' => 'Insufficient data',
            'en-NZ' => 'Insufficient data',
            'no-NO' => 'For lite data',
            'ru-RU' => 'Недостаточно данных',
        ] as $language => $expected) {
            Yii::$app->language = $language;
            $this->assertSame($expected, $this->render($poll, '@voted_ages'));
        }
    }

    private function render(Poll $poll, string $template): string
    {
        $data = array_fill_keys([
            'date', 'dateIso', 'dateDay', 'dateMonth', 'dateYear', 'pollUrl', 'pollAbsoluteUrl',
            'languageName', 'languageCode', 'languageId', 'authorName', 'authorId',
            'votesTotal', 'votesLastMonth', 'votesThisMonth', 'votesThisYear',
            'votesPreviousYear', 'registeredVotes', 'guestVotes', 'commentsCount',
            'tagsHtml', 'tagsPlain', 'tagsCount', 'optionsCount', 'optionsTitles', 'optionsListHtml',
        ], '');
        $data['mostPopular'] = null;

        $method = new ReflectionMethod(Poll::class, 'getStaticInfoReplacements');
        return strtr($template, $method->invoke($poll, $data, $template));
    }
}

class PollWithVotedAgesToken extends Poll
{
    public array $votedAges = [];

    public function attributes()
    {
        return ['title', 'id'];
    }

    public function getVotedAgeIntervals(): array
    {
        return $this->votedAges;
    }
}
