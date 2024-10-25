<?php

namespace App\Exchanges\Services;

use App\Exchanges\DataObjects\PriceDto;
use Illuminate\Support\Collection;

abstract class AbstractApiClient
{
    public function symbol(string $baseCurrency, string $quoteCurrency): string
    {
        return $baseCurrency . $this->symbolDelimiter() . $quoteCurrency;
    }

    abstract public function getCurrentPrice(string $baseCurrency, string $quoteCurrency): ?PriceDto;

    abstract public function getAllSymbolPrices(): Collection;

    abstract public function exchangeName(): string;

    public function symbolDelimiter(): string
    {
        return '';
    }
}
