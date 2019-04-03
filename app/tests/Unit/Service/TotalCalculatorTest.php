<?php

namespace App\Tests\Unit\Service;

use App\Entity;
use App\Service\BasketPrice\TotalCalculator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class TotalCalculatorTest extends TestCase
{
    /**
     * @dataProvider products
     * @test
     */
    public function testCalculateTotal($products, $expectedPrice)
    {
        $basket = Entity\Basket::fromProperties('1234', 0);

        foreach($products as $product) {
            $productToBeAdded = clone $product;
            $basket->addProduct($productToBeAdded);
        }

        $totalCalculator = new TotalCalculator();

        $total = $totalCalculator->calculateTotal($basket);

        Assert::assertEquals($expectedPrice, $total);
    }

    /**
     * @return array
     */
    public function products()
    {
        $productA = Entity\BasketProduct::fromProperties('A', 1, 50);
        $productB = Entity\BasketProduct::fromProperties('B', 1, 30);
        $productC = Entity\BasketProduct::fromProperties('C', 1, 20);
        $productD = Entity\BasketProduct::fromProperties('D', 1, 15);

        $data = [
            [[$productA], 50],
            [[$productA, $productB], 80],
            [[$productC, $productD, $productB, $productA], 115],
            [[$productA, $productA], 100],
            [[$productA, $productA, $productA], 150],
            [[$productA, $productA, $productA, $productB, $productB], 210]
        ];

        return $data;
    }
}
