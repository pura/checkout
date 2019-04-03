<?php

namespace App\Command;

class AddToBasket 
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
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * 
     * @return AddToBasket
     */
    public function setSku(string $sku): AddToBasket
    {
        $this->sku = $sku;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity ?: 1;
    }

    /**
     * @param int $quantity
     *
     * @return AddToBasket
     */
    public function setQuantity(int $quantity): AddToBasket
    {
        $this->quantity = $quantity;

        return $this;
    }
}