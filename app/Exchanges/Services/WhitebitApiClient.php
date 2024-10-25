<?php

namespace App\Exchanges\Services;

use App\Exchanges\DataObjects\PriceDto;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Http;

class WhitebitApiClient
{
    protected string $exchange = 'Whitebit';

    public function __construct(
        #[Config('services.whitebit.base_url')]
        protected string $apiUrl
    ) {}

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
        $price = (float) $data[$symbol]['last_price'];
        return new PriceDto($this->exchange, $price);
    }
}
