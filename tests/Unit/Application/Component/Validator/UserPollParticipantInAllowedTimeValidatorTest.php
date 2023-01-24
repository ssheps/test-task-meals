<?php

declare(strict_types=1);

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\UserParticipantPollInNotAllowedTimeException;
use Meals\Application\Component\Validator\UserPollParticipantInAllowedTimeValidator;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class UserPollParticipantInAllowedTimeValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful()
    {
        $validator = new UserPollParticipantInAllowedTimeValidator();
        $participantTime = new \DateTimeImmutable('monday 08:00am');
        verify($validator->validate($participantTime))->null();
    }

    public function testSuccessfulEarliestAvailableDate()
    {
        $validator = new UserPollParticipantInAllowedTimeValidator();
        $participantTime = new \DateTimeImmutable('monday 06:00am');
        verify($validator->validate($participantTime))->null();
    }

    public function testSuccessfulLatestAvailableDate()
    {
        $validator = new UserPollParticipantInAllowedTimeValidator();
        $participantTime = new \DateTimeImmutable('monday 10:00pm');
        verify($validator->validate($participantTime))->null();
    }

    public function testFail()
    {
        $this->expectException(UserParticipantPollInNotAllowedTimeException::class);

        $validator = new UserPollParticipantInAllowedTimeValidator();
        $participantTime = new \DateTimeImmutable('wednesday 08:00am');

        $validator->validate($participantTime);
    }
}
