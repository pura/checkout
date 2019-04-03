<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BaseIntegrationTestCase extends KernelTestCase
{
    public function setUp(): void
    {
        self::bootKernel();

        /**
         * @var $session SessionInterface
         */
        $session = self::$container->get(SessionInterface::class);

        if ($session->has('products')) {
            $session->remove('products');
        }

        $products = [
            [
                'sku' => 'A',
                'price' => 50,
                'offers' => [
                    [
                        'quantity' => 3,
                        'price' => 130
                    ]
                ]
            ],
            [
                'sku' => 'B',
                'price' => 30,
                'offers' => [
                    [
                        'quantity' => 2,
                        'price' => 45
                     ]
                ]
            ],
            [
                'sku' => 'C',
                'price' => 20
            ],
            [
                'sku' => 'D',
                'price' => 15
            ],
        ];

        $session->set('products', json_encode($products));
    }

    public function tearDown(): void
    {
        self::bootKernel();
        /**
         * @var $session SessionInterface
         */
        $session = self::$container->get(SessionInterface::class);

        if ($session->has('products')) {
            $session->remove('products');
        }
    }
}