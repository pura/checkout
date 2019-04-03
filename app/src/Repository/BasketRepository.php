<?php

namespace App\Repository;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Entity\Product;
use App\Service\Storage;
use Exception;

/**
 * Have made use of session to store data only for the purpose of demo.
 * This would be using either doctrine with designed database
 * or any document like mongodb.
 *
 * @package App\Repository
 */
class BasketRepository
{
    const STORAGE_KEY = 'basket';

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * ProductRepository constructor.
     *
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param $value
     *
     * @return Product|null
     *
     * @throws Exception
     */
    public function findById($value): ?Basket
    {
        if (!$this->storage->has(self::STORAGE_KEY)) {
            return null;
        }

        $basket = $this->storage->get(self::STORAGE_KEY);
        $decodedBasket = json_decode($basket, true);

        if (!$decodedBasket) {
            return null;
        }

        foreach ($decodedBasket as $basket) {
            if ($basket['id'] == $value) {
                $basket = Basket::fromProperties(
                    $basket['id'],
                    $basket['total']
                );

                $basket = Basket::updateTotalAfterOffer($basket['totalAfterOffer']);

                return $basket;
            }
        }

        return null;
    }

    /**
     * @param Basket $basket
     */
    public function saveBasket(Basket $basket): void
    {
        $basketArray = [
            'id' => $basket->getId(),
            'total' => $basket->getTotal(),
            'totalAfterOffer' => $basket->getTotalAfterOffer(),
            'products' => $this->searializeBasketProducts($basket->getBasketProducts())
        ];

        $this->storage->set(self::STORAGE_KEY, json_encode($basketArray));
    }

    /**
     * @return Basket|null
     */
    public function getCurrentBasket(): ?Basket
    {
        if ($this->storage->has(self::STORAGE_KEY)) {
            $serializedBasket = $this->storage->get(self::STORAGE_KEY);
            $decodedBasket = json_decode($serializedBasket, true);

            $basket = Basket::fromProperties(
                $decodedBasket['id'],
                $decodedBasket['total']
            );

            $products = $decodedBasket['products'];

            foreach ($products as $product) {
                $product = BasketProduct::fromProperties($product['sku'],
                    $product['quantity'],
                    $product['price']);

                $basket->addProduct($product);
            }

            return $basket;
        }

        return null;
    }

    /**
     * @param array $basketProducts
     * @return array
     */
    private function searializeBasketProducts(?array $basketProducts): array
    {
        $basketProductArray = [];

        if (is_array($basketProducts)) {
            foreach ($basketProducts as $basketProduct) {
                $basketProductArray[] = [
                    'sku' => $basketProduct->getSku(),
                    'quantity' => $basketProduct->getQuantity(),
                    'price' => $basketProduct->getPrice()
                ];
            }
        }

        return $basketProductArray;
    }
}
