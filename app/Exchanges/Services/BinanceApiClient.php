<?php

namespace App\Exchanges\Services;

use App\Exchanges\DataObjects\PriceDto;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class BinanceApiClient extends AbstractApiClient
{
    public function __construct(
        #[Config('services.binance.base_url')]
        protected string $apiUrl
    )
    {
    }

    public function exchangeName(): string
    {
        return 'Binance';
    }

    public function symbol(string $baseCurrency, string $quoteCurrency): string
    {
        return $baseCurrency . $this->symbolDelimiter() . $quoteCurrency;
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
        $res = Http::get($this->apiUrl . '/api/v3/ticker/price');
        return collect($res->json() ?? [])
            ->map(function (array $data) {
                return new PriceDto(
                    exchange: $this->exchangeName(),
                    price: $data['price'],
                    symbol: $data['symbol'],
                    symbolDelimiter: $this->symbolDelimiter(),
                );
            });

    }
}
