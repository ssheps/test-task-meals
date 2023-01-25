<?php

declare(strict_types=1);

namespace tests\Meals\Functional\Interactor;

use DateTime;
use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\Exception\PollHasNotDishException;
use Meals\Application\Component\Validator\Exception\PollIsNotActiveException;
use Meals\Application\Component\Validator\Exception\UserParticipantPollInNotAllowedTimeException;
use Meals\Application\Component\Validator\Exception\UserPollResultExistException;
use Meals\Application\Feature\PollResult\UseCase\EmployeeGetsPollResult\Interactor;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use SlopeIt\ClockMock\ClockMock;
use tests\Meals\Functional\Fake\Provider\FakeDishProvider;
use tests\Meals\Functional\Fake\Provider\FakeEmployeeProvider;
use tests\Meals\Functional\Fake\Provider\FakePollProvider;
use tests\Meals\Functional\Fake\Provider\FakePollResultProvider;
use tests\Meals\Functional\FunctionalTestCase;

class EmployeeGetsPollResultTest extends FunctionalTestCase
{
    public function testSuccessful()
    {
        ClockMock::freeze($this->getAllowedDatetime());

        $dish = $this->getDish();
        $pollResult = $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $dish,
            $this->getPoll($dish)
        );
        verify($pollResult)->equals($pollResult);

        ClockMock::reset();
    }

    public function testNotAllowedDatetime()
    {
        $this->expectException(UserParticipantPollInNotAllowedTimeException::class);

        ClockMock::freeze($this->getNotAllowedDatetime());

        $dish = $this->getDish();
        $pollResult = $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $dish,
            $this->getPoll($dish)
        );

        verify($pollResult)->equals($pollResult);

        ClockMock::reset();
    }

    public function testEarliestAllowedDatetime()
    {
        ClockMock::freeze(new DateTime("2023-01-23 06:00:00am"));

        $dish = $this->getDish();
        $pollResult = $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $dish,
            $this->getPoll($dish)
        );
        verify($pollResult)->equals($pollResult);

        ClockMock::reset();
    }

    public function testLatestAllowedDatetime()
    {
        ClockMock::freeze(new DateTime("2023-01-23 10:00:00pm"));

        $dish = $this->getDish();
        $pollResult = $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $dish,
            $this->getPoll($dish)
        );
        verify($pollResult)->equals($pollResult);

        ClockMock::reset();
    }

    public function testUserHasNotPermissions()
    {
        $this->expectException(AccessDeniedException::class);

        ClockMock::freeze($this->getAllowedDatetime());

        $dish = $this->getDish();
        $pollResult = $this->performTestMethod(
            $this->getEmployeeWithNoPermissions(),
            $dish,
            $this->getPoll($dish)
        );

        verify($pollResult)->equals($pollResult);

        ClockMock::reset();
    }

    public function testPollIsNotActive()
    {
        $this->expectException(PollIsNotActiveException::class);

        ClockMock::freeze($this->getAllowedDatetime());

        $dish = $this->getDish();
        $pollResult = $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $dish,
            $this->getPoll($dish, false)
        );
        verify($pollResult)->equals($pollResult);

        ClockMock::reset();
    }

    public function testPollHasNotThisDish()
    {
        $this->expectException(PollHasNotDishException::class);

        ClockMock::freeze($this->getAllowedDatetime());

        $poll = $this->getPoll(
            new Dish(
                2,
                'Some other dish',
                'Some other dish description'
            )
        );
        $pollResult = $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $this->getDish(),
            $poll
        );
        verify($pollResult)->equals($pollResult);

        ClockMock::reset();
    }

    public function testPollResultAlreadyExists()
    {
        $this->expectException(UserPollResultExistException::class);

        ClockMock::freeze($this->getAllowedDatetime());

        $dish = $this->getDish();
        $poll = $this->getPoll($dish);
        $employee = $this->getEmployeeWithPermissions();
        $this->getContainer()->get(FakePollResultProvider::class)->setPollResult(
            new PollResult(
                1,
                $poll,
                $employee,
                $dish,
                $employee->getFloor(),
            )
        );
        $pollResult = $this->performTestMethod(
            $employee,
            $dish,
            $poll
        );
        verify($pollResult)->equals($pollResult);

        ClockMock::reset();
    }

    private function performTestMethod(Employee $employee, Dish $dish, Poll $poll): PollResult
    {
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);
        $this->getContainer()->get(FakePollProvider::class)->setPoll($poll);
        $this->getContainer()->get(FakeDishProvider::class)->setDish($dish);

        return $this->getContainer()->get(Interactor::class)->getPollResult(
            $employee->getId(),
            $dish->getId(),
            $poll->getId()
        );
    }

    private function getEmployeeWithPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithPermissions(): User
    {
        return new User(
            1,
            new PermissionList(
                [
                    new Permission(Permission::PARTICIPATION_IN_POLLS),
                ]
            ),
        );
    }

    private function getEmployeeWithNoPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithNoPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithNoPermissions(): User
    {
        return new User(
            1,
            new PermissionList([]),
        );
    }

    private function getPoll(Dish $dish, bool $isActive = true): Poll
    {
        return new Poll(
            1,
            $isActive,
            new Menu(
                1,
                'title',
                new DishList([$dish]),
            )
        );
    }

    private function getDish(): Dish
    {
        return new Dish(
            1,
            'Dish',
            'Dish description'
        );
    }

    private function getAllowedDatetime(): DateTime
    {
        return new DateTime("2023-01-23 08:00:00");
    }

    private function getNotAllowedDatetime(): DateTime
    {
        return new DateTime("2023-01-25 05:00:00");
    }
}
