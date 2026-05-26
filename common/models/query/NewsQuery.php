<?php

namespace common\models\query;

class NewsQuery extends \yii\db\ActiveQuery
{
    // Для фронтенду тут тримаємо бізнес-правило: показуємо тільки опубліковані новини.
    public function published(): self
    {
        return $this->andWhere(['is_published' => 1]);
    }

    public function all($db = null)
    {
        return parent::all($db);
    }

    public function one($db = null)
    {
        return parent::one($db);
    }
}
