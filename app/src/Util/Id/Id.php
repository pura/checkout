<?php

namespace App\Util\Id;

use Ramsey\Uuid\Uuid;
use Exception;

/**
 * Class Id
 *
 * @package App\Util\Id
 */
class Id
{
    /**
     * @return string
     *
     * @throws Exception
     */
    public static function create(): string
    {
        return $uuid4 = Uuid::uuid4()->toString();
    }
}