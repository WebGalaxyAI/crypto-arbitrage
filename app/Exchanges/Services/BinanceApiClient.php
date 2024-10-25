<?php

namespace App\Exchanges\Services;

use App\Exchanges\DataObjects\PriceDto;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Http;

class BinanceApiClient
{
    protected string $exchange = 'Binance';

    public function __construct(
        #[Config('services.binance.base_url')]
        protected string $apiUrl
    )
    {
    }

    public function symbol(string $baseCurrency, string $quoteCurrency): string
    {
        return $baseCurrency . $quoteCurrency;
    }

    public function getCurrentPrice(string $baseCurrency, string $quoteCurrency): ?PriceDto
    {
        $res = Http::get($this->apiUrl . '/api/v3/ticker/price', [
            'symbol' => $this->symbol($baseCurrency, $quoteCurrency),
        ]);
        if (!isset($res->json()['price'])) {
            return null;
        }
        $price = (float)$res->json()['price'];
        return new PriceDto($this->exchange, $price);
    }
}
