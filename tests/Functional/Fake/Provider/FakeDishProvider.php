<?php

declare(strict_types=1);

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Domain\Dish\Dish;

class FakeDishProvider implements DishProviderInterface
{
    public function getDish(int $dishId): Dish
    {
        // TODO: Implement getPollResult() method.
    }
}
