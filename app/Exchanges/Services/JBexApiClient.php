<?php

namespace App\Exchanges\Services;

use App\Exchanges\DataObjects\PriceDto;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Http;

class JBexApiClient
{
    protected string $exchange = 'JBEX';

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
        $response = Http::get($this->apiUrl . '/ticker/24hr', [
            'symbol' => $this->symbol($baseCurrency, $quoteCurrency),
        ]);

        if (!isset($response->json()['lastPrice'])) {
            return null;
        }

        $price = (float) $response->json()['lastPrice'];

        return new PriceDto($this->exchange, $price);
    }
}
