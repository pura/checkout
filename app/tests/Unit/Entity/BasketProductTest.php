<?php

namespace App\Tests\Unit\Entity;

use App\Entity\BasketProduct;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class BasketProductTest extends TestCase
{
    public function testFromPropertiesCreatesNewBasketProduct()
    {
        $basketProduct = BasketProduct::fromProperties('1234', 2, 30);

        Assert::assertInstanceOf(BasketProduct::class, $basketProduct);
    }

    public function testAddQuantityUpdatesQuantity()
    {
        $basketProduct = BasketProduct::fromProperties('1234', 2, 30);
        BasketProduct::addQuantity($basketProduct, 3);
        Assert::assertEquals(5, $basketProduct->getQuantity());
    }
}