<?php

namespace App\Event;

use App\Entity\Basket;
use Symfony\Component\EventDispatcher\Event;

class AddToBasket extends Event
{
    /**
     * @var Basket
     */
    private $basket;

    /**
     * @return Basket
     */
    public function getBasket(): Basket
    {
        return $this->basket;
    }

    /**
     * @param Basket $basket
     *
     * @return AddToBasket
     */
    public static function fromProperties(
        Basket $basket)
    {
        $event = new self();
        $event->basket = $basket;

        return $event;
    }

    /**
     * @param AddToBasket $addToBasket
     * @param Basket $basket
     *
     * @return AddToBasket
     */
    public static function updateBasket(AddToBasket $addToBasket,
                                        Basket $basket)
    {
        $addToBasket->basket = $basket;

        return $addToBasket;
    }
}