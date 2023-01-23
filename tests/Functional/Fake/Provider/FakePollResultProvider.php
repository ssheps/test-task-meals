<?php

declare(strict_types=1);

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollList;
use Meals\Domain\Poll\PollResult;

class FakePollResultProvider implements PollResultProviderInterface
{
    public function getPollResult(int $employeeId, int $pollId): PollResult
    {
        // TODO: Implement getPollResult() method.
    }
}
