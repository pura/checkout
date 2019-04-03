<?php

namespace App\Tests\Unit\Service;

use App\Entity\Product;
use App\Exception\ProductNotFoundException;
use App\Repository\ProductRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Mockery;
use App\Service;

class ProductServiceTest extends TestCase
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var Service\Product
     */
    private $service;

    public function setUp(): void
    {
        $this->productRepository = Mockery::mock(ProductRepository::class);
        $this->service = new Service\Product($this->productRepository);
    }

    /**
     * @test
     */
    public function findProductBySkuReturnsProduct()
    {
        $sku = 'A';
        $product = Product::fromProperties('A', 10);

        $this->productRepository->shouldReceive('findBySku')
            ->with($sku)
            ->andReturn($product);

        Assert::assertInstanceOf(Product::class, $this->service->findProductBySku('A'));
    }

    /**
     * @test
     */
    public function findProductBySkuReturnsProductThrowsException()
    {
        $this->expectException(ProductNotFoundException::class);
        $this->expectExceptionMessage('product A not found');

        $sku = 'A';
        $this->productRepository->shouldReceive('findBySku')
            ->with($sku)
            ->andReturn(null);

        $this->service->findProductBySku('A');
    }
}