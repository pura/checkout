<?php

namespace App\CommandHandler;

use App\Command;
use App\Entity;
use App\Exception\ProductNotFoundException;
use App\Service;
use App\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Exception;

/**
 * Handles Adding of Products to the basket
 *
 * @package App\CommandHandler
 */
class AddToBasketHandler 
{
    /**
     * @var Service\Basket
     */
    private $basketService;

    /**
     * @var Service\Product
     */
    private $productService;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param Service\Basket $basketService
     * @param Service\Product $productService
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(Service\Basket $basketService,
                                Service\Product $productService,
                                EventDispatcherInterface $eventDispatcher
    )
    {
        $this->basketService = $basketService;
        $this->productService = $productService;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Command\AddToBasket $addToBasket
     *
     * @return Entity\Basket
     *
     * @throws Exception
     */
    public function handle(Command\AddToBasket $addToBasket)
    {
        $product = $this->productService->findProductBySku($addToBasket->getSku());

        if (!$product instanceof Entity\Product) {
            throw new ProductNotFoundException('product does not exists');
        }

        $basketProduct = Entity\BasketProduct::fromProperties($product->getSku(),
            $addToBasket->getQuantity(),
            $product->getPrice());

        $basket = $this->basketService->addProduct($basketProduct);

        $event = Event\AddToBasket::fromProperties($basket);
        $this->eventDispatcher->dispatch(Event\Events::EVENT_ADD_TO_BASKET, $event);

        $this->basketService->save($event->getBasket());

        /**
         * At the moment this is out of the scope for this test,
         * but in reality, handler would only return basketId and may be some other data but not whole of basket.
         * And there would be a query handler to fetch the data related to basket when required.
         */
        return $event->getBasket();
    }
}
