<?php

namespace App\Exchanges\DataObjects;

class PriceDto
{
    public function __construct(
        public string  $exchange,
        public float   $price,
        public string  $symbol,
        public string  $symbolDelimiter = '',
        public ?string $baseCurrency = null,
        public ?string $quoteCurrency = null
    )
    {
    }
}
