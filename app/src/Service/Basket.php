<?php

namespace App\Service;

use App\Entity;
use App\Repository\BasketRepository;
use App\Util\Id\Id;
use Exception;

/**
 * Class Basket
 *
 * Service to connect to Basket and related repositories
 *
 * @package App\Service
 */
class Basket
{
    /**
     * @var BasketRepository
     */
    private $basketRepository;

    /**
     * @param BasketRepository $basketRepository
     */
    public function __construct(BasketRepository $basketRepository)
    {
        $this->basketRepository = $basketRepository;
    }

    /**
     * @return Entity\Basket
     *
     * @throws Exception
     */
    public function getOrCreateBasketFromStorage(): Entity\Basket
    {
        $basket = $this->basketRepository->getCurrentBasket();

        if (!$basket instanceof Entity\Basket) {
            $id = Id::create();
            $total = 0;

            $basket = Entity\Basket::fromProperties($id, $total);
            $this->basketRepository->saveBasket($basket);
        }

        return $basket;
    }

    /**
     * @param Entity\Basket $basket
     */
    public function save(Entity\Basket $basket): void
    {
        $this->basketRepository->saveBasket($basket);
    }

    /**
     * @param Entity\BasketProduct $basketProduct
     *
     * @return Entity\Basket
     *
     * @throws Exception
     */
    public function addProduct(Entity\BasketProduct $basketProduct): Entity\Basket
    {
        $basket = $this->getOrCreateBasketFromStorage();
        $basket->addProduct($basketProduct);

        return $basket;
    }
}