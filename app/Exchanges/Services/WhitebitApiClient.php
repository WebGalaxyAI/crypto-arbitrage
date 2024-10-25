<?php

namespace App\Exchanges\Services;

use App\Exchanges\DataObjects\PriceDto;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class WhitebitApiClient extends AbstractApiClient
{
    public function __construct(
        #[Config('services.whitebit.base_url')]
        protected string $apiUrl
    )
    {
    }

    public function symbol(string $baseCurrency, string $quoteCurrency): string
    {
        return $baseCurrency . '_' . $quoteCurrency;
    }

    public function getCurrentPrice(string $baseCurrency, string $quoteCurrency): ?PriceDto
    {
        $response = Http::get($this->apiUrl . '/public/ticker');

        $data = $response->json();
        $symbol = $this->symbol($baseCurrency, $quoteCurrency);
        if (!isset($data[$symbol]['last_price'])) {
            return null;
        }
        $price = (float)$data[$symbol]['last_price'];
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
        $res = Http::get($this->apiUrl . '/public/ticker');
        return collect($res->json() ?? [])
            ->map(function (array $data, $key) {
                return new PriceDto(
                    exchange: $this->exchangeName(),
                    price: $data['last_price'],
                    symbol: $key,
                    symbolDelimiter: $this->symbolDelimiter(),
                );
            });

    }

    public function exchangeName(): string
    {
        return 'Whitebit';
    }

    public function symbolDelimiter(): string
    {
        return '_';
    }
}
