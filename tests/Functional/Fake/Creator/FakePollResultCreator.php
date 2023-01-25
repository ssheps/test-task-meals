<?php

namespace tests\Meals\Functional\Fake\Creator;

use Meals\Application\Component\Creator\PollResultCreatorInterface;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;

class FakePollResultCreator implements PollResultCreatorInterface
{
    public function createPollResult(Employee $employee, Dish $dish, Poll $poll): PollResult
    {
        return new PollResult(
            1,
            $poll,
            $employee,
            $dish,
            $employee->getFloor()
        );
    }
}