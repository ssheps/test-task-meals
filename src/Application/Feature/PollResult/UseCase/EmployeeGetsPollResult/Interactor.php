<?php

declare(strict_types=1);

namespace Meals\Application\Feature\PollResult\UseCase\EmployeeGetsPollResult;

use DateTimeImmutable;
use Meals\Application\Component\Creator\PollResultCreatorInterface;
use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Application\Component\Provider\EmployeeProviderInterface;
use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Application\Component\Validator\PollHasDishValidator;
use Meals\Application\Component\Validator\PollIsActiveValidator;
use Meals\Application\Component\Validator\UserHasAccessToPollsParticipantValidator;
use Meals\Application\Component\Validator\UserHasNotPollResultValidator;
use Meals\Application\Component\Validator\UserPollParticipantInAllowedTimeValidator;
use Meals\Domain\Poll\PollResult;

class Interactor
{
    public function __construct(
        private EmployeeProviderInterface $employeeProvider,
        private PollProviderInterface $pollProvider,
        private DishProviderInterface $dishProvider,
        private UserHasAccessToPollsParticipantValidator $userHasAccessToPollParticipantValidator,
        private PollIsActiveValidator $pollIsActiveValidator,
        private PollHasDishValidator $pollHasDishValidator,
        private UserHasNotPollResultValidator $userHasNotPollResultValidator,
        private UserPollParticipantInAllowedTimeValidator $allowedTimeValidator,
        private PollResultCreatorInterface $pollResultCreator
    ) {}

    public function getPollResult(int $employeeId, int $dishId, int $pollId): PollResult
    {
        $pollParticipantTime = new DateTimeImmutable();
        $employee = $this->employeeProvider->getEmployee($employeeId);
        $poll = $this->pollProvider->getPoll($pollId);
        $dish = $this->dishProvider->getDish($dishId);

        $this->userHasAccessToPollParticipantValidator->validate($employee->getUser());
        $this->pollIsActiveValidator->validate($poll);
        $this->userHasNotPollResultValidator->validate($employee, $poll);
        $this->pollHasDishValidator->validate($poll, $dish);
        $this->allowedTimeValidator->validate($pollParticipantTime);

        return $this->pollResultCreator->createPollResult($poll, $employee, $dish);
    }
}
