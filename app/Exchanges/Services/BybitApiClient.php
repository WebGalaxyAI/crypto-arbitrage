<?php

namespace App\Exchanges\Services;

use App\Exchanges\DataObjects\PriceDto;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Http;

class BybitApiClient
{
    protected string $exchange = 'Bybit';

    public function __construct(
        #[Config('services.bybit.base_url')]
        protected string $apiUrl
    ) {}

    public function symbol(string $baseCurrency, string $quoteCurrency): string
    {
        return $baseCurrency . $quoteCurrency;
    }

    public function getCurrentPrice(string $baseCurrency, string $quoteCurrency): ?PriceDto
    {
        $response = Http::get($this->apiUrl . '/tickers', [
            'category' => 'spot',
            'symbol' => $this->symbol($baseCurrency, $quoteCurrency),
        ]);
        $data = $response->json();
        if (!isset($data['result']['list'][0]['lastPrice'])) {
            return null;
        }
        $price = (float) $data['result']['list'][0]['lastPrice'];
        return new PriceDto($this->exchange, $price);
    }
}
