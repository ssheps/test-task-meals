<?php

namespace tests\Meals\Functional\Fake\Creator;

use Meals\Application\Component\Creator\PollResultCreatorInterface;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;

class FakePollResultCreator implements PollResultCreatorInterface
{
    public function createPollResult(Poll $poll, Employee $employee, Dish $dish): PollResult
    {
        // TODO: Implement createPollResult() method.
    }
}