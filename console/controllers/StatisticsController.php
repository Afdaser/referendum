<?php

namespace console\controllers;

use common\models\Language;
use common\models\OptionGuestVote;
use common\models\OptionVote;
use common\models\Poll;
use common\models\User;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Готує недорогі для читання показники лендінгу поза вебзапитом.
 */
class StatisticsController extends Controller
{
    /**
     * Оновлює загальний знімок і знімки всіх мовних піддоменів.
     */
    public function actionRefresh(): int
    {
        try {
            $statistics = ['all' => $this->buildSnapshot(null)];
            foreach (Language::find()->select('id')->column() as $languageId) {
                $statistics[(int) $languageId] = $this->buildSnapshot((int) $languageId);
            }
            // Записуємо всі зрізи одним атомарним оновленням файлового кешу.
            if (!\Yii::$app->statisticsCache->set('main-page-statistics', $statistics, 0)) {
                throw new \RuntimeException('Не вдалося зберегти показники головної сторінки.');
            }
        } catch (\Throwable $exception) {
            $this->stderr($exception->getMessage() . PHP_EOL);

            return ExitCode::UNSPECIFIED_ERROR;
        }

        $this->stdout("Показники головної сторінки оновлено.\n");

        return ExitCode::OK;
    }

    /**
     * Рахує один зріз; голоси охоплюють авторизованих і гостьових учасників.
     */
    private function buildSnapshot(?int $languageId): array
    {
        $polls = Poll::find()->alias('poll');
        $registeredVotes = OptionVote::find()->alias('vote')
            ->innerJoin('{{%poll_option}} option', 'option.id = vote.option_id')
            ->innerJoin('{{%poll}} poll', 'poll.id = option.poll_id');
        $guestVotes = OptionGuestVote::find()->alias('vote')
            ->innerJoin('{{%poll_option}} option', 'option.id = vote.option_id')
            ->innerJoin('{{%poll}} poll', 'poll.id = option.poll_id');

        if ($languageId === null) {
            $usersCount = (int) User::find()->count();
        } else {
            // DISTINCT не дає повторно врахувати користувача з дубльованим мовним зв'язком.
            $usersCount = (int) User::find()->alias('user')
                ->innerJoin('{{%user_language}} user_language', 'user_language.user_id = user.id')
                ->andWhere(['user_language.language_id' => $languageId])
                ->count('DISTINCT user.id');
            $polls->andWhere(['poll.poll_language_id' => $languageId]);
            $registeredVotes->andWhere(['poll.poll_language_id' => $languageId]);
            $guestVotes->andWhere(['poll.poll_language_id' => $languageId]);
        }

        return [
            'users' => $usersCount,
            'polls' => (int) $polls->count(),
            'votes' => (int) $registeredVotes->count() + (int) $guestVotes->count(),
            'updatedAt' => time(),
        ];
    }
}
