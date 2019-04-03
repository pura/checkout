<?php

namespace App\Tests\Unit\Entity;

use App\Entity\ProductOffer;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ProductOfferTest extends TestCase
{
    public function testCreateFromProperties()
    {
        $product = ProductOffer::fromProperties('12345', 10, 50);
        Assert::assertInstanceOf(ProductOffer::class, $product);
    }
}