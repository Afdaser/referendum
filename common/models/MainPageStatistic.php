<?php

namespace common\models;

use common\components\ActiveRecord;

/**
 * Збережений знімок показників для головної сторінки.
 *
 * Рядок без language_id містить підсумок усього домену, а рядки з мовою —
 * показники відповідного піддомену.
 *
 * @property int $id
 * @property int|null $language_id
 * @property int $users_count
 * @property int $polls_count
 * @property int $votes_count
 * @property int $updated_at
 */
class MainPageStatistic extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%main_page_statistic}}';
    }

    /**
     * Повертає готовий для відображення знімок або безпечні нульові значення.
     */
    public static function getSnapshot(?int $languageId): array
    {
        $query = self::find();
        $languageId === null
            ? $query->andWhere(['language_id' => null])
            : $query->andWhere(['language_id' => $languageId]);
        $statistic = $query->one();

        return [
            'users' => (int) ($statistic->users_count ?? 0),
            'polls' => (int) ($statistic->polls_count ?? 0),
            'votes' => (int) ($statistic->votes_count ?? 0),
            'updatedAt' => $statistic ? (int) $statistic->updated_at : null,
        ];
    }
}
