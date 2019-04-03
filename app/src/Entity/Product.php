<?php

namespace App\Entity;

class Product 
{
    /**
     * @var string
     */
    private $sku;

    /**
     * @var string
     */
    private $price;

    /**
     * @var ProductOffer[]
     */
    private $offers;

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @return ProductOffer[]
     */
    public function getOffers(): ?array
    {
        return $this->offers;
    }

    /**
     * @param ProductOffer $offer
     *
     * @return Product
     */
    public function addOffer(ProductOffer $offer): Product
    {
        $this->offers[] = $offer;

        return $this;
    }


    /**
     * @param string $sku
     * @param string $price
     *
     * @return Product
     */
    public static function fromProperties(
        string $sku,
        string $price
    )
    {
        $product = new self();
        $product->sku = $sku;
        $product->price = $price;

        return $product;
    }
}