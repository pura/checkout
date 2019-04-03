<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Exception;

class ProductNotFoundException extends BadRequestHttpException
{
    const MSG = 'Product not found.';

    public function __construct(string $message = self::MSG,
                                Exception $previous = null,
                                int $code = 0,
                                array $headers = [])
    {
        parent::__construct($message, $previous, $code, $headers);
    }
}