<?php

declare(strict_types=1);

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Application\Component\Validator\Exception\UserPollResultExistException;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\Poll;

class UserHasNotPollResultValidator
{
    public function __construct(
        private PollResultProviderInterface $pollResultProvider
    ) {}
    public function validate(Employee $employee, Poll $poll): void
    {
        if ($this->pollResultProvider->getPollResult($employee->getId(), $poll->getId())) {
            throw new UserPollResultExistException();
        }
    }
}
