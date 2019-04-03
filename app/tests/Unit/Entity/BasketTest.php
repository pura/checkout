<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Util\Id\Id;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    public function testFromPropertiesCreatesNewBasket()
    {
        $id = Id::create();
        $basket = Basket::fromProperties($id, 50);

        Assert::assertInstanceOf(Basket::class, $basket);
    }


    public function testUpdateTotalCreatesNewBasket()
    {
        $id = Id::create();
        $basket = Basket::fromProperties($id, 50);

        Assert::assertEquals(50, $basket->getTotal());
        Assert::assertEquals(50, $basket->getTotalAfterOffer());

        Basket::updateTotal($basket, 70, 60);
        Assert::assertEquals(70, $basket->getTotal());
        Assert::assertEquals(60, $basket->getTotalAfterOffer());
    }

    public function testHasProductReturnsFalseWhenThereIsNoProduct()
    {
        $id = Id::create();
        $basket = Basket::fromProperties($id, 50);

        $basketProduct = BasketProduct::fromProperties('1234', 1, 100);

        Assert::assertFalse($basket->hasProduct($basketProduct));
    }

    public function testHasProductReturnsFalseWhenProductGivenDoesNotExists()
    {
        $id = Id::create();
        $basket = Basket::fromProperties($id, 50);

        $basketProduct = BasketProduct::fromProperties('1234', 1, 100);
        $basket->addProduct($basketProduct);

        $basketProduct2 = BasketProduct::fromProperties('12345', 1, 100);

        Assert::assertFalse($basket->hasProduct($basketProduct2));
    }

    public function testHasProductReturnsTrueWhenProductGivenExists()
    {
        $id = Id::create();
        $basket = Basket::fromProperties($id, 50);

        $basketProduct = BasketProduct::fromProperties('1234', 1, 100);
        $basket->addProduct($basketProduct);

        Assert::assertTrue($basket->hasProduct($basketProduct));
    }

    public function testAddProduct()
    {
        $id = Id::create();
        $basket = Basket::fromProperties($id, 50);

        $basketProduct = BasketProduct::fromProperties('1234', 1, 100);
        $basket->addProduct($basketProduct);

        $basketProduct = BasketProduct::fromProperties('3456', 1, 100);
        $basket->addProduct($basketProduct);

        Assert::assertCount(2, $basket->getBasketProducts());
    }

    public function testAddProductAddQuantityForSameProduct()
    {
        $id = Id::create();
        $basket = Basket::fromProperties($id, 50);

        $basketProduct = BasketProduct::fromProperties('1234', 1, 100);
        $basket->addProduct($basketProduct);

        $basketProduct = BasketProduct::fromProperties('1234', 1, 100);
        $basket->addProduct($basketProduct);

        $basketProduct = BasketProduct::fromProperties('3456', 1, 100);
        $basket->addProduct($basketProduct);

        Assert::assertCount(2, $basket->getBasketProducts());
        Assert::assertEquals(2, $basket->getBasketProductFromSku('1234')->getQuantity());
        Assert::assertEquals(1, $basket->getBasketProductFromSku('3456')->getQuantity());
    }

    public function testGetBasketProductFromSkuReturnsNullIfNotFound()
    {
        $id = Id::create();
        $basket = Basket::fromProperties($id, 50);
        Assert::assertNull($basket->getBasketProductFromSku('1234'));

        $basketProduct = BasketProduct::fromProperties('1234', 1, 100);
        $basket->addProduct($basketProduct);

        Assert::assertNull($basket->getBasketProductFromSku('2345'));
        Assert::assertInstanceOf(BasketProduct::class, $basket->getBasketProductFromSku('1234'));
    }
}