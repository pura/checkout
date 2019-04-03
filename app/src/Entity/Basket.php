<?php

namespace App\Entity;

class Basket
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var float
     */
    private $total;

    /**
     * @var float
     */
    private $totalAfterOffer;

    /**
     * @var array
     */
    private $basketProducts;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @return float
     */
    public function getTotalAfterOffer(): float
    {
        return $this->totalAfterOffer;
    }

    /**
     * @return array
     */
    public function getBasketProducts(): ?array
    {
        return $this->basketProducts;
    }

    /**
     * @param BasketProduct $product
     *
     * @return bool
     */
    public function hasProduct(BasketProduct $product): bool
    {
        $basketProducts = $this->getBasketProducts();

        if (!$basketProducts) {
            return false;
        }

        foreach ($basketProducts as $basketProduct) {
            if ($product->getSku() == $basketProduct->getSku()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param BasketProduct $product
     *
     * @return Basket
     */
    public function addProduct(BasketProduct $product): Basket
    {
        if ($this->hasProduct($product)) {
            $this->updateProductQuantity($product);

            return $this;
        }

        $this->basketProducts[] = $product;

        return $this;
    }

    /**
     * @param BasketProduct $productToBeAdded
     *
     * @return Basket
     */
    public function updateProductQuantity(BasketProduct $productToBeAdded): Basket
    {
        $basketProducts = $this->getBasketProducts();

        if (!$basketProducts) {
            return false;
        }

        foreach ($basketProducts as $basketProduct) {
            if ($productToBeAdded->getSku() == $basketProduct->getSku()) {
                BasketProduct::addQuantity($basketProduct, $productToBeAdded->getQuantity());
            }
        }

        return $this;
    }

    /**
     * @param string $sku
     *
     * @return BasketProduct|null
     */
    public function getBasketProductFromSku(string $sku): ?BasketProduct
    {
        $basketProducts = $this->getBasketProducts();

        if (!$basketProducts) {
            return null;
        }

        foreach ($basketProducts as $basketProduct) {
            if ($sku == $basketProduct->getSku()) {
                return $basketProduct;
            }
        }

        return null;
    }


    /**
     * @param string $id
     * @param float $total
     *
     * @return Basket
     */
    public static function fromProperties(
        string $id,
        float $total
    )
    {
        $basket = new self();
        $basket->id = $id;
        $basket->total = $total;
        $basket->totalAfterOffer = $total;

        return $basket;
    }


    /**
     * @param Basket $basket
     * @param float $newTotal
     * @param float|null $totalAfterOffer
     *
     * @return Basket
     */
    public static function updateTotal(
        Basket $basket,
        float $newTotal,
        ?float $totalAfterOffer
    ): Basket
    {
        $basket->total = $newTotal;
        $basket->totalAfterOffer = $totalAfterOffer;

        return $basket;
    }
}