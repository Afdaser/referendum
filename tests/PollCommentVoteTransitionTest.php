<?php

use common\models\PollComment;
use PHPUnit\Framework\TestCase;

class PollCommentVoteTransitionTest extends TestCase
{
    /**
     * Перевіряємо Reddit-подібні переходи: голос, скасування і зміна напрямку.
     *
     * @dataProvider voteTransitions
     */
    public function testVoteTransition(int $previous, int $requested, int $vote, int $delta): void
    {
        self::assertSame(
            ['vote' => $vote, 'delta' => $delta],
            PollComment::resolveRatingVote($previous, $requested)
        );
    }

    public function voteTransitions(): array
    {
        return [
            'новий плюс' => [0, 1, 1, 1],
            'скасування плюса' => [1, 1, 0, -1],
            'зміна плюса на мінус' => [1, -1, -1, -2],
            'новий мінус' => [0, -1, -1, -1],
            'скасування мінуса' => [-1, -1, 0, 1],
            'зміна мінуса на плюс' => [-1, 1, 1, 2],
        ];
    }
}
