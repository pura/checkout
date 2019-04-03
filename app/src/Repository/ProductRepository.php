<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductOffer;
use App\Service\Storage;

/**
 * Have made use of session to store data only for the purpose of demo.
 * This would be using either doctrine with designed database
 * or any document like mongodb.
 *
 * @package App\Repository
 */
class ProductRepository
{
    const STORAGE_KEY = 'products';

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param string $sku
     *
     * @return Product|null
     */
    public function findBySku(string $sku): ?Product
    {
        if (!$this->storage->has(self::STORAGE_KEY)) {
            return null;
        }

        $products = $this->storage->get('products');
        $decodedProducts = json_decode($products, true);

        foreach ($decodedProducts as $product) {
            if (array_key_exists('sku', $product)) {
                if ($product['sku'] == $sku) {
                    return Product::fromProperties(
                        $product['sku'],
                        $product['price']
                    );
                }
            }
        }

        return null;
    }

    /**
     * @param string $sku
     *
     * @return array|null
     */
    public function findOffersBySku(string $sku): array
    {
        $offers = [];

        if (!$this->storage->has(self::STORAGE_KEY)) {
            return $offers;
        }

        $products = $this->storage->get('products');
        $decodedProducts = json_decode($products, true);

        foreach ($decodedProducts as $product) {
            if (array_key_exists('sku', $product)) {
                if ($product['sku'] == $sku) {
                    if (array_key_exists('offers', $product) && $product['offers']) {
                        foreach ($product['offers'] as $offer) {
                            $offers[] = ProductOffer::fromProperties(
                                $sku,
                                $offer['quantity'],
                                $offer['price']
                            );
                        }

                    }
                }
            }
        }

        return $offers;
    }
}