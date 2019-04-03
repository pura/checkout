<?php

namespace App\Entity;

class ProductOffer
{
    /**
     * @var string
     */
    private $sku;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var float
     */
    private $price;

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param float $price
     *
     * @return ProductOffer
     */
    public static function fromProperties(string $sku,
                                          int $quantity,
                                          float $price) : ProductOffer
    {
        $productOffers = new self();
        $productOffers->sku = $sku;
        $productOffers->quantity = $quantity;
        $productOffers->price = $price;

        return $productOffers;
    }
}