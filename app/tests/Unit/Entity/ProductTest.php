<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Product;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testCreateFromProperties()
    {
        $product = Product::fromProperties('12345', 10);
        Assert::assertInstanceOf(Product::class, $product);
    }
}