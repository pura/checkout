<?php

namespace App\Service\BasketPrice;

use App\Entity;
use App\Service;

/**
 * Class TotalCalculator
 *
 * @package App\Service\BasketPrice
 */
class OfferCalculator
{
    /**
     * @var Service\Product
     */
    protected $productService;

    /**
     * @param Service\Product $productService
     */
    public function __construct(Service\Product $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @param Entity\Basket $basket
     *
     * @return float
     */
    public function calculateTotal(Entity\Basket $basket): float
    {
        $basketProducts = $basket->getBasketProducts();
        $basketTotalWithOffer = 0;

        /**
         * @var Entity\BasketProduct $basketProduct
         */
        foreach ($basketProducts as $basketProduct) {
            $basketProductQuantity = $basketProduct->getQuantity();
            $productPrice = $basketProduct->getPrice();
            $totalOfProduct = $basketProductQuantity * $productPrice;

            $activeOffers = $this->productService->findProductOffersBySku($basketProduct->getSku());

            $offerAppliedForTheProduct = false;
            if ($activeOffers) {
                /**
                 * @var ProductOffer $offer
                 */
                foreach ($activeOffers as $offer) {

                    $offerQuantity = $offer->getQuantity();
                    $offerPrice = $offer->getPrice();

                    if ($basketProductQuantity >= $offerQuantity) {
                        $offerTimes = floor($basketProductQuantity/$offerQuantity);
                        $remainingProductQuantityOutOfOrder = $basketProductQuantity % $offerQuantity;
                        $totalOfProductWithOffer = ($offerPrice * $offerTimes) + ($remainingProductQuantityOutOfOrder * $productPrice);
                        $basketTotalWithOffer += $totalOfProductWithOffer;
                        $offerAppliedForTheProduct = true;

                        /**
                         * @todo only applies one offer at the moment for one product.
                         * Depending on strategy, how multiple offer should work,
                         * this would have been done differently
                         */
                        break;
                    }
                }
            }

            if (false === $offerAppliedForTheProduct) {
                $basketTotalWithOffer += $totalOfProduct;
            }
        }

        return $basketTotalWithOffer;
    }
}