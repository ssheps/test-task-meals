<?php

declare(strict_types=1);

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Domain\Poll\PollResult;

class FakePollResultProvider implements PollResultProviderInterface
{
    private ?PollResult $pollResult = null;

    public function getPollResult(int $employeeId, int $pollId): ?PollResult
    {
        return $this->pollResult;
    }

    public function setPollResult(?PollResult $pollResult)
    {
        $this->pollResult = $pollResult;
    }
}
