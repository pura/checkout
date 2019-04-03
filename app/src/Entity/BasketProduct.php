<?php

namespace App\Entity;

class BasketProduct
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
     * @return BasketProduct
     */
    public static function fromProperties(
        string $sku,
        int $quantity,
        float $price
    )
    {
        $product = new self();
        $product->sku = $sku;
        $product->quantity = $quantity;
        $product->price = $price;

        return $product;
    }

    /**
     * @param BasketProduct $basketProduct
     * @param int $quantityToAdd
     *
     * @return BasketProduct
     */
    public static function addQuantity(BasketProduct $basketProduct,
                                          int $quantityToAdd): BasketProduct
    {
        $basketProduct->quantity = $basketProduct->getQuantity() + $quantityToAdd;

        return $basketProduct;
    }
}