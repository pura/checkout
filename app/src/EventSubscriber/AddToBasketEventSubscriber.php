<?php

namespace App\EventSubscriber;

use App\Entity;
use App\Event;
use App\Service;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class AddToBasketEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var Service\BasketPrice\TotalCalculator
     */
    protected $basketTotalCalculator;

    /**
     * @var Service\BasketPrice\OfferCalculator
     */
    protected $basketOfferCalculator;

    /**
     * @param Service\BasketPrice\TotalCalculator $totalCalculator
     * @param Service\BasketPrice\OfferCalculator $offerCalculator
     */
    public function __construct(Service\BasketPrice\TotalCalculator $totalCalculator,
                                Service\BasketPrice\OfferCalculator $offerCalculator
    )
    {
        $this->basketTotalCalculator = $totalCalculator;
        $this->basketOfferCalculator = $offerCalculator;
    }

    /**
     * @param Event\AddToBasket $event
     */
    public function onAddToBasket(Event\AddToBasket $event): void
    {
        $basket = $event->getBasket();

        $totalWithOutOffer = $this->basketTotalCalculator->calculateTotal($basket);
        $totalWithOffer = $this->basketOfferCalculator->calculateTotal($basket);

        $basket = Entity\Basket::updateTotal($basket, $totalWithOutOffer, $totalWithOffer);

        Event\AddToBasket::updateBasket($event, $basket);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            Event\Events::EVENT_ADD_TO_BASKET => 'onAddToBasket'
        ];
    }
}