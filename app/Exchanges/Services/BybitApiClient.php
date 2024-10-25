<?php

namespace App\Exchanges\Services;

use App\Exchanges\DataObjects\PriceDto;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class BybitApiClient extends AbstractApiClient
{
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
        $response = Http::get($this->apiUrl . '/v5/market/tickers', [
            'category' => 'spot',
            'symbol' => $this->symbol($baseCurrency, $quoteCurrency),
        ]);
        $data = $response->json();
        if (!isset($data['result']['list'][0]['lastPrice'])) {
            return null;
        }
        $price = (float) $data['result']['list'][0]['lastPrice'];
        return new PriceDto(
            exchange: $this->exchangeName(),
            price: $price,
            symbol: $this->symbol($baseCurrency, $quoteCurrency),
            symbolDelimiter: $this->symbolDelimiter(),
            baseCurrency: $baseCurrency,
            quoteCurrency: $quoteCurrency
        );
    }

    public function getAllSymbolPrices(): Collection
    {
        $res = Http::get($this->apiUrl . '/v5/market/tickers', [
            'category' => 'spot',
        ]);
        return collect($res->json()['result']['list'] ?? [])
            ->map(function (array $data) {
                return new PriceDto(
                    exchange: $this->exchangeName(),
                    price: $data['lastPrice'],
                    symbol: $data['symbol'],
                    symbolDelimiter: $this->symbolDelimiter(),
                );
            });

    }

    public function exchangeName(): string
    {
        return 'Bybit';
    }
}
