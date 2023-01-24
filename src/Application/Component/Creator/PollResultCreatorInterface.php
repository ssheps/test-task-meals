<?php

namespace Meals\Application\Component\Creator;

use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;

interface PollResultCreatorInterface
{
    public function createPollResult(
        Employee $employee,
        Dish $dish,
        Poll $poll
    ): PollResult;
}