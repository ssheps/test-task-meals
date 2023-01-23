<?php

declare(strict_types=1);

namespace Meals\Application\Component\Validator;

use DateTimeImmutable;
use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Application\Component\Validator\Exception\UserParticipantPollInNotAllowedTimeException;
use Meals\Application\Component\Validator\Exception\UserPollResultExistException;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\AllowedDateTime\AllowedDateTime;
use Meals\Domain\Poll\Poll;

class UserPollParticipantInAllowedTimeValidator
{
    public function validate(DateTimeImmutable $participantTime): void
    {
        $startDatetime = new DateTimeImmutable(AllowedDateTime::START_DATETIME);
        $endDatetime = new DateTimeImmutable(AllowedDateTime::END_DATETIME);

        if (!($participantTime->getTimestamp() >= $startDatetime->getTimestamp() &&
            $participantTime->getTimestamp() <= $endDatetime->getTimestamp()
        )) {
            throw new UserParticipantPollInNotAllowedTimeException();
        }
    }
}
