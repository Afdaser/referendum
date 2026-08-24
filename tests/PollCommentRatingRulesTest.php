<?php

use common\models\PollCommentRating;
use PHPUnit\Framework\TestCase;

/**
 * Перевіряє розділення правил унікальності для користувачів і гостей.
 */
class PollCommentRatingRulesTest extends TestCase
{
    public function testGuestAndUserUniquenessRulesHaveSeparateScopes(): void
    {
        $model = new PollCommentRating();
        $uniqueRules = array_values(array_filter($model->rules(), static function (array $rule): bool {
            return ($rule[1] ?? null) === 'unique';
        }));

        $this->assertCount(2, $uniqueRules);
        $this->assertSame(['poll_comment_id', 'user_id'], $uniqueRules[0]['targetAttribute']);
        $this->assertSame(['poll_comment_id', 'guest_key'], $uniqueRules[1]['targetAttribute']);

        // Гість проходить лише IP-правило, а авторизований користувач — лише user_id-правило.
        $this->setModelAttributes($model, ['user_id' => null]);
        $this->assertFalse($uniqueRules[0]['when']($model));
        $this->assertTrue($uniqueRules[1]['when']($model));

        $this->setModelAttributes($model, ['user_id' => 42]);
        $this->assertTrue($uniqueRules[0]['when']($model));
        $this->assertFalse($uniqueRules[1]['when']($model));
    }

    /**
     * Заповнює AR без підключення до БД: цьому тесту потрібні лише умови правил моделі.
     */
    private function setModelAttributes(PollCommentRating $model, array $attributes): void
    {
        $property = new ReflectionProperty(yii\db\BaseActiveRecord::class, '_attributes');
        $property->setAccessible(true);
        $property->setValue($model, $attributes);
    }
}
