<?php

namespace App\Tests\Unit\Service;

use App\Service\BasketPrice\OfferCalculator;
use App\Service;
use App\Entity;
use PHPUnit\Framework\Assert;
use Mockery;
use PHPUnit\Framework\TestCase;

class OfferCalculatorTest extends TestCase
{
    /**
     * @var Service\Product
     */
    private $productService;

    /**
     * @var OfferCalculator
     */
    private $offerCalculator;

    public function setUp(): void
    {
        $this->productService = Mockery::mock(Service\Product::class);
        $this->offerCalculator = new OfferCalculator($this->productService);
    }

    /**
     * @dataProvider products
     * @test
     */
    public function testCalculateTotal($products, $expectedPrice)
    {

        $productAOffer = Entity\ProductOffer::fromProperties('A', 3, 130);
        $productBOffer = Entity\ProductOffer::fromProperties('B', 2, 45);

        $this->productService->shouldReceive('findProductOffersBySku')
            ->with('A')
            ->andReturn([$productAOffer]);

        $this->productService->shouldReceive('findProductOffersBySku')
            ->with('B')
            ->andReturn([$productBOffer]);

        $this->productService->shouldReceive('findProductOffersBySku')
            ->with('C')
            ->andReturn([]);

        $this->productService->shouldReceive('findProductOffersBySku')
            ->with('D')
            ->andReturn([]);

        $basket = Entity\Basket::fromProperties('1234', 0);

        foreach($products as $product) {
            $productToBeAdded = clone $product;
            $basket->addProduct($productToBeAdded);
        }

        $total = $this->offerCalculator->calculateTotal($basket);

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
            [[$productA, $productA, $productA], 130],
            [[$productA, $productA, $productA, $productB, $productB], 175]
        ];

        return $data;
    }
}
