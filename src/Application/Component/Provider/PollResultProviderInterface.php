<?php

declare(strict_types=1);

namespace Meals\Application\Component\Provider;

use Meals\Domain\Poll\PollResult;

interface PollResultProviderInterface
{
    public function getPollResult(int $employeeId, int $pollId): ?PollResult;

}
