<?php

namespace App\Console\Commands;

use App\Exchanges\Services\BinanceApiClient;
use App\Exchanges\Services\BybitApiClient;
use App\Exchanges\Services\JBexApiClient;
use App\Exchanges\Services\PoloniexApiClient;
use App\Exchanges\Services\WhitebitApiClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Concurrency;

class CurrencyPairPrice extends Command
{
    protected $signature = 'app:check-cur-pair-price {base?} {quote?}';

    protected $description = 'Check currency pair price';

    public function handle()
    {
        $baseCurrency = $this->argument('base') ? strtoupper($this->argument('base')) : strtoupper($this->ask('Enter the base currency (e.g., BTC):'));

        $quoteCurrency = $this->argument('quote') ? strtoupper($this->argument('quote')) : strtoupper($this->ask('Enter the quote currency (e.g., USDT):'));

        $prices = Concurrency::run([
            fn () => app(BinanceApiClient::class)->getCurrentPrice($baseCurrency, $quoteCurrency),
            fn () => app(JBexApiClient::class)->getCurrentPrice($baseCurrency, $quoteCurrency),
            fn () => app(PoloniexApiClient::class)->getCurrentPrice($baseCurrency, $quoteCurrency),
            fn () => app(BybitApiClient::class)->getCurrentPrice($baseCurrency, $quoteCurrency),
            fn () => app(WhitebitApiClient::class)->getCurrentPrice($baseCurrency, $quoteCurrency),
        ]);

        $this->displayPrices($baseCurrency, $quoteCurrency, $prices);
    }

    private function displayPrices(string $baseCurrency, string $quoteCurrency, array $prices)
    {
        $prices = collect(array_filter($prices))->sortBy(fn($priceDto) => $priceDto->price);

        $tableData = [];
        foreach ($prices as $priceDto) {
            if ($priceDto) {
                $tableData[] = [
                    'Exchange' => $priceDto->exchange,
                    'Price' => $priceDto->price,
                ];
            }
        }

        $symbol = $baseCurrency . '/' . $quoteCurrency;
        $this->info("Symbol: $symbol");
        $this->table(['Exchange', 'Price'], $tableData);
    }
}
