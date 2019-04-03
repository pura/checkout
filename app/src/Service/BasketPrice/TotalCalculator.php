<?php

namespace App\Service\BasketPrice;

use App\Entity;

/**
 * Class TotalCalculator
 *
 * @package App\Service\BasketPrice
 */
class TotalCalculator
{
    /**
     * @param Entity\Basket $basket
     *
     * @return float
     */
    public function calculateTotal(Entity\Basket $basket): float
    {
        $basketProducts = $basket->getBasketProducts();
        $basketTotalWithoutOffer = 0;

        /**
         * @var Entity\BasketProduct $basketProduct
         */
        foreach ($basketProducts as $basketProduct) {
            $basketProductQuantity = $basketProduct->getQuantity();
            $productPrice = $basketProduct->getPrice();
            $totalOfProduct = $basketProductQuantity * $productPrice;
            $basketTotalWithoutOffer += $totalOfProduct;
        }

        return $basketTotalWithoutOffer;
    }
}