<?php

declare(strict_types=1);

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollHasNotDishException;
use Meals\Application\Component\Validator\PollHasDishValidator;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\Poll;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class PollHasDishValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful()
    {
        $dish = $this->prophesize(Dish::class);
        $dishList = $this->prophesize(DishList::class);
        $menu = $this->prophesize(Menu::class);
        $poll = $this->prophesize(Poll::class);

        $dishList->hasDish($dish)->willReturn(true);
        $menu->getDishes()->willReturn($dishList);
        $poll->getMenu()->willReturn($menu);

        $validator = new PollHasDishValidator();
        verify($validator->validate($poll->reveal(), $dish->reveal()))->null();
    }

    public function testFail()
    {
        $this->expectException(PollHasNotDishException::class);

        $dish = $this->prophesize(Dish::class);
        $dishList = $this->prophesize(DishList::class);
        $menu = $this->prophesize(Menu::class);
        $poll = $this->prophesize(Poll::class);

        $dishList->hasDish($dish)->willReturn(false);
        $menu->getDishes()->willReturn($dishList);
        $poll->getMenu()->willReturn($menu);

        $validator = new PollHasDishValidator();
        $validator->validate($poll->reveal(), $dish->reveal());
    }
}
