<?php

namespace App\Exchanges\Services;

use App\Exchanges\DataObjects\PriceDto;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Http;

class PoloniexApiClient
{
    protected string $exchange = 'Poloniex';

    public function __construct(
        #[Config('services.poloniex.base_url')]
        protected string $apiUrl
    ) {}

    public function symbol(string $baseCurrency, string $quoteCurrency): string
    {
        return $baseCurrency . '_' . $quoteCurrency;
    }

    public function getCurrentPrice(string $baseCurrency, string $quoteCurrency): ?PriceDto
    {
        $response = Http::get($this->apiUrl . '/markets/' . $this->symbol($baseCurrency, $quoteCurrency) . '/price');
        if (!isset($response->json()['price'])) {
            return null;
        }
        $price = (float) $response->json()['price'];
        return new PriceDto($this->exchange, $price);
    }
}
