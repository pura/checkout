<?php

namespace App\Tests\Unit\Service;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Repository\BasketRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Mockery;
use App\Service;

class BasketServiceTest extends TestCase
{
    /**
     * @var BasketRepository
     */
    private $basketRepository;

    /**
     * @var Service\Basket
     */
    private $service;

    public function setUp(): void
    {
        $this->basketRepository = Mockery::mock(BasketRepository::class);
        $this->service = new Service\Basket($this->basketRepository);
    }

    public function testGetOrCreateBasketFromStorage()
    {
        $this->markTestSkipped('Written for temporary measure');
    }

    public function testSave()
    {
        $basket = Basket::fromProperties('12345', 100);
        $this->basketRepository->shouldReceive('saveBasket')
            ->with($basket)
            ->once();

        $this->service->save($basket);
    }

    public function testAddProduct()
    {
        $basket = Basket::fromProperties('1234566',100);
        $this->basketRepository->shouldReceive('getCurrentBasket')
            ->andReturn($basket);

        $basketProduct = BasketProduct::fromProperties('1234', 3,100);
        Assert::assertCount(1, $this->service->addProduct($basketProduct)->getBasketProducts());
    }
}