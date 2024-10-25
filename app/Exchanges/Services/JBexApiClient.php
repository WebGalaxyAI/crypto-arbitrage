<?php

namespace App\Exchanges\Services;

use App\Exchanges\DataObjects\PriceDto;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class JBexApiClient extends AbstractApiClient
{
    public function __construct(
        #[Config('services.jbex.base_url')]
        protected string $apiUrl
    ) {}

    public function symbol(string $baseCurrency, string $quoteCurrency): string
    {
        return $baseCurrency . $quoteCurrency;
    }

    public function getCurrentPrice(string $baseCurrency, string $quoteCurrency): ?PriceDto
    {
        $response = Http::get($this->apiUrl . '/openapi/quote/v1/ticker/24hr', [
            'symbol' => $this->symbol($baseCurrency, $quoteCurrency),
        ]);

        if (!isset($response->json()['lastPrice'])) {
            return null;
        }

        $price = (float) $response->json()['lastPrice'];

        return new PriceDto(
            exchange: $this->exchangeName(),
            price: $price,
            symbol: $this->symbol($baseCurrency, $quoteCurrency),
            symbolDelimiter: $this->symbolDelimiter(),
            baseCurrency: $baseCurrency,
            quoteCurrency: $quoteCurrency
        );
    }

    public function exchangeName(): string
    {
        return 'JBEX';
    }

    public function getAllSymbolPrices(): Collection
    {
        $res = Http::get($this->apiUrl . '/openapi/quote/v1/ticker/24hr');
        return collect($res->json() ?? [])
            ->map(function (array $data) {
                return new PriceDto(
                    exchange: $this->exchangeName(),
                    price: $data['lastPrice'],
                    symbol: $data['symbol'],
                    symbolDelimiter: $this->symbolDelimiter(),
                );
            });

    }
}
