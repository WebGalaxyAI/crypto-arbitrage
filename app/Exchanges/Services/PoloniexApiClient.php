<?php

namespace App\Exchanges\Services;

use App\Exchanges\DataObjects\PriceDto;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class PoloniexApiClient extends AbstractApiClient
{
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
        $res = Http::get($this->apiUrl . '/markets/price');
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

    public function exchangeName(): string
    {
        return 'Poloniex';
    }

    public function symbolDelimiter(): string
    {
        return '_';
    }
}
