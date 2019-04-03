<?php

namespace App\Tests\Integration\CommandHandler;

use App\Command\AddToBasket;
use App\CommandHandler\AddToBasketHandler;
use App\Entity\Basket;
use App\Entity\Product;
use App\Tests\Integration\BaseIntegrationTestCase;
use PHPUnit\Framework\Assert;

class AddToBasketHandlerTest extends BaseIntegrationTestCase
{
    /**
     * @var AddToBasketHandler
     */
    private $addToBasketHandler;

    public function setUp(): void
    {
        parent::setUp();
        $this->addToBasketHandler = self::$container->get(AddToBasketHandler::class);
    }

    /**
     * @test
     */
    public function handleAddsTheProductIfNotExistsAlready()
    {
        $sku = 'A';
        $price = 50;
        $product1 = Product::fromProperties(
            $sku, $price
        );

        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku);

        /**
         * @var Basket $basket
         */
        $basket = $this->addToBasketHandler->handle($addToBasket);

        Assert::assertEquals(1, count($basket->getBasketProducts()));
        Assert::assertEquals(1, $basket->getBasketProductFromSku($product1->getSku())->getQuantity());
        Assert::assertEquals(50, $basket->getTotal());
    }

    /**
     * @test
     */
    public function handleMultipleProductsNoOffer()
    {
        $sku = 'A';
        $sku2 = 'B';
        $sku3 = 'C';
        $sku4 = 'D';

        //adds third product
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku3)
            ->setQuantity(1);
        $this->addToBasketHandler->handle($addToBasket);

        //product 1
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku)
            ->setQuantity(1);
        $this->addToBasketHandler->handle($addToBasket);

        //adds 4th product
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku4)
            ->setQuantity(1);
        $this->addToBasketHandler->handle($addToBasket);

        //product 2
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku2)
            ->setQuantity(1);
        $basket = $this->addToBasketHandler->handle($addToBasket);

        Assert::assertEquals(4, count($basket->getBasketProducts()));
        Assert::assertEquals(115, $basket->getTotal());
    }

    /**
     * @test
     */
    public function handleAddsTheQuantityIfProductExistsAlready()
    {
        $sku = 'A';
        $price = 50;
        $product1 = Product::fromProperties(
            $sku, $price
        );

        $sku2 = 'B';
        $price2 = 30;
        $product2 = Product::fromProperties(
            $sku2, $price2
        );

        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku)
            ->setQuantity(1);

        $this->addToBasketHandler->handle($addToBasket);

        //adds the same product twice
        $this->addToBasketHandler->handle($addToBasket);

        //adds second product
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku2)
            ->setQuantity(1);

        $basket = $this->addToBasketHandler->handle($addToBasket);

        Assert::assertEquals(2, count($basket->getBasketProducts()));
        Assert::assertEquals(2, $basket->getBasketProductFromSku($product1->getSku())->getQuantity());
        Assert::assertEquals(1, $basket->getBasketProductFromSku($product2->getSku())->getQuantity());
        Assert::assertEquals(130, $basket->getTotal());
    }

    /**
     * @test
     */
    public function handleOfferWithMultipleQuantity()
    {
        $sku = 'A';
        $price = 50;
        $product1 = Product::fromProperties(
            $sku, $price
        );

        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku)
            ->setQuantity(3);

        $basket = $this->addToBasketHandler->handle($addToBasket);

        Assert::assertEquals(1, count($basket->getBasketProducts()));
        Assert::assertEquals(3, $basket->getBasketProductFromSku($product1->getSku())->getQuantity());
        Assert::assertEquals(150, $basket->getTotal());
        Assert::assertEquals(130, $basket->getTotalAfterOffer());
    }


    /**
     * @test
     */
    public function handleOfferWithMultipleProduct()
    {
        $sku = 'A';
        $price = 50;
        $product1 = Product::fromProperties(
            $sku, $price
        );

        $sku2 = 'B';
        $price = 30;
        $product2 = Product::fromProperties(
            $sku2, $price
        );

        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku)
            ->setQuantity(3);
        $this->addToBasketHandler->handle($addToBasket);

        //adds second product
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku2)
            ->setQuantity(2);

        $basket = $this->addToBasketHandler->handle($addToBasket);

        Assert::assertEquals(2, count($basket->getBasketProducts()));
        Assert::assertEquals(3, $basket->getBasketProductFromSku($product1->getSku())->getQuantity());
        Assert::assertEquals(210, $basket->getTotal());
        Assert::assertEquals(175, $basket->getTotalAfterOffer());
    }

    /**
     * @test
     */
    public function handleOfferWithMultipleProductInDifferentOrder()
    {
        $sku = 'A';
        $price = 50;
        $product1 = Product::fromProperties(
            $sku, $price
        );

        $sku2 = 'B';
        $price = 30;
        $product2 = Product::fromProperties(
            $sku2, $price
        );

        //product 1
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku)
            ->setQuantity(2);
        $this->addToBasketHandler->handle($addToBasket);

        //product 2
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku2)
            ->setQuantity(1);

        $this->addToBasketHandler->handle($addToBasket);

        //product 1 again
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku)
            ->setQuantity(1);
        $this->addToBasketHandler->handle($addToBasket);

        //adds second product
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku2)
            ->setQuantity(1);

        $basket = $this->addToBasketHandler->handle($addToBasket);

        Assert::assertEquals(2, count($basket->getBasketProducts()));
        Assert::assertEquals(3, $basket->getBasketProductFromSku($product1->getSku())->getQuantity());
        Assert::assertEquals(210, $basket->getTotal());
        Assert::assertEquals(175, $basket->getTotalAfterOffer());
    }

    /**
     * @test
     */
    public function handleOfferWithMultipleProductInDifferentOrderAndDifferentQuantity()
    {
        $sku = 'A';
        $price = 50;
        $product1 = Product::fromProperties(
            $sku, $price
        );

        $sku2 = 'B';
        $price = 30;
        $product2 = Product::fromProperties(
            $sku2, $price
        );

        $sku3 = 'C';
        $price3 = 20;
        $product3 = Product::fromProperties(
            $sku3, $price3
        );

        $sku4 = 'D';
        $price4 = 15;
        $product4 = Product::fromProperties(
            $sku4, $price4
        );

        //product 1
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku)
            ->setQuantity(3);
        $this->addToBasketHandler->handle($addToBasket);

        //product 2
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku2)
            ->setQuantity(1);

        $this->addToBasketHandler->handle($addToBasket);

        //product 1 again
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku)
            ->setQuantity(5);
        $this->addToBasketHandler->handle($addToBasket);

        //adds second product
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku2)
            ->setQuantity(3);
        $this->addToBasketHandler->handle($addToBasket);

        //adds third product
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku3)
            ->setQuantity(1);
        $this->addToBasketHandler->handle($addToBasket);

        //adds 4th product
        $addToBasket = new AddToBasket();
        $addToBasket->setSku($sku4)
            ->setQuantity(1);
        $basket = $this->addToBasketHandler->handle($addToBasket);

        Assert::assertEquals(4, count($basket->getBasketProducts()));
        Assert::assertEquals(8, $basket->getBasketProductFromSku($product1->getSku())->getQuantity());
        Assert::assertEquals(4, $basket->getBasketProductFromSku($product2->getSku())->getQuantity());
        Assert::assertEquals(1, $basket->getBasketProductFromSku($product3->getSku())->getQuantity());
        Assert::assertEquals(1, $basket->getBasketProductFromSku($product4->getSku())->getQuantity());
        Assert::assertEquals(555, $basket->getTotal());
        Assert::assertEquals(485, $basket->getTotalAfterOffer());
    }
}