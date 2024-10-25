<?php

namespace App\Exchanges\DataObjects;

class PriceDto
{
    public function __construct(
        public string $exchange,
        public float  $price
    )
    {
    }
}
