<?php

namespace App\Service;

use App\Entity;
use App\Exception\ProductNotFoundException;
use App\Repository\ProductRepository;

/**
 * Facade to connect to Repository
 *
 * @package App\Service
 */
class Product
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * Product constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param string $sku
     *
     * @return Entity\Product
     *
     * @throws ProductNotFoundException
     */
    public function findProductBySku(string $sku): Entity\Product
    {
        $product = $this->productRepository->findBySku($sku);

        if (!$product instanceof Entity\Product) {
            throw new ProductNotFoundException(sprintf('product %s not found', $sku));
        }

        return $product;
    }

    /**
     * @param string $sku
     *
     * @return null|array
     */
    public function findProductOffersBySku(string $sku): ?array
    {
        $offers = $this->productRepository->findOffersBySku($sku);

        return $offers;
    }
}