<?php

namespace App\Tests\Unit\EventSubscriber;

use App\Entity\Basket;
use App\Event\AddToBasket;
use App\EventSubscriber\AddToBasketEventSubscriber;
use PHPUnit\Framework\TestCase;
use Mockery;
use App\Service;
use PHPUnit\Framework\Assert;

class AddToBasketEventSubscriberTest extends TestCase
{

    /**
     * @var Service\BasketPrice\TotalCalculator
     */
    private $basketTotalCalculator;

    /**
     * @var Service\BasketPrice\OfferCalculator
     */
    private $basketOfferCalculator;

    /**
     * @var AddToBasketEventSubscriber
     */
    private $subscriber;

    public function setUp(): void
    {
        $this->basketTotalCalculator = Mockery::mock(Service\BasketPrice\TotalCalculator::class);
        $this->basketOfferCalculator = Mockery::mock(Service\BasketPrice\OfferCalculator::class);
        $this->subscriber = new AddToBasketEventSubscriber($this->basketTotalCalculator, $this->basketOfferCalculator);
    }

    /**
     * @test
     */
    public function testOnAddToBasketUpdatesTheEventWithTotal()
    {
        $basket = Basket::fromProperties('1234', 20);

        $this->basketTotalCalculator->shouldReceive('calculateTotal')
            ->with($basket)
            ->andReturn(100);

        $this->basketOfferCalculator->shouldReceive('calculateTotal')
            ->with($basket)
            ->andReturn(80);

        $event = AddToBasket::fromProperties($basket);
        /**
         * @var Basket $updatedBasket
         */
        $this->subscriber->onAddToBasket($event);

        Assert::assertEquals(100, $basket->getTotal());
        Assert::assertEquals(80, $basket->getTotalAfterOffer());
    }
}