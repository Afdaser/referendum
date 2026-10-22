<?php

use common\models\User;
use PHPUnit\Framework\TestCase;

class AgeIntervalsTest extends TestCase
{
    protected function setUp(): void
    {
        Yii::setAlias('@common', dirname(__DIR__) . '/common');
    }

    public function testAdultGroupsHaveNoGapsOrOverlaps(): void
    {
        $groups = User::getUserAgeIntervalList();
        $this->assertCount(6, $groups);
        foreach (range(0, 120) as $age) {
            $matches = 0;
            foreach (array_keys($groups) as $id) {
                $bounds = User::getAgesByIntervalIndex($id);
                $matches += $age >= $bounds['min'] && $age <= $bounds['max'] ? 1 : 0;
            }
            $this->assertSame($age < 18 ? 0 : 1, $matches, 'Age ' . $age);
        }
        $this->assertNull(User::getAgesByIntervalIndex(0));
        $this->assertNull(User::getAgesByIntervalIndex(7));
    }
}
