<?php

namespace App\Console\Commands;

use App\Exchanges\DataObjects\PriceDto;
use App\Exchanges\Services\BinanceApiClient;
use App\Exchanges\Services\BybitApiClient;
use App\Exchanges\Services\JBexApiClient;
use App\Exchanges\Services\PoloniexApiClient;
use App\Exchanges\Services\WhitebitApiClient;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Concurrency;

class CalculateArbitrageProfit extends Command
{
    protected $signature = 'app:calculate-arbitrage-profit';

    protected $description = 'Command description';

    protected Collection $commonPairPrices;

    public function handle()
    {
        $this->commonPairPrices = collect();

        collect(Concurrency::run([
            fn() => app(BinanceApiClient::class)->getAllSymbolPrices(),
            fn() => app(JBexApiClient::class)->getAllSymbolPrices(),
            fn() => app(PoloniexApiClient::class)->getAllSymbolPrices(),
            fn() => app(BybitApiClient::class)->getAllSymbolPrices(),
            fn() => app(WhitebitApiClient::class)->getAllSymbolPrices(),
        ]))
            ->map(function (Collection $exchangePrices) {
                $exchangePrices->map(function (PriceDto $priceDto) {
                    $commonSymbol = str_replace($priceDto->symbolDelimiter, '', $priceDto->symbol);
                    if (!$this->commonPairPrices->has($commonSymbol)) {
                        $this->commonPairPrices->put($commonSymbol, collect());
                    }
                    $this->commonPairPrices->put($commonSymbol, $this->commonPairPrices->get($commonSymbol)->push($priceDto));
                });
            });

        $arbitragePairs = $this->commonPairPrices->filter(fn(Collection $prices) => $prices->count() === 5);
        $this->renderResults($arbitragePairs);
    }

    protected function renderResults(Collection $arbitragePairs)
    {
        $tableData = [];

        foreach ($arbitragePairs as $symbol => $prices) {
            $lowestPriceDto = $prices->sortBy('price')->first();
            $highestPriceDto = $prices->sortByDesc('price')->first();

            $profitPercentage = (($highestPriceDto->price - $lowestPriceDto->price) / $lowestPriceDto->price) * 100;

            $tableData[] = [
                'Currency Pair' => $symbol,
                'Lowest Price' => number_format($lowestPriceDto->price, 8),
                'Lowest Price Exchange' => $lowestPriceDto->exchange,
                'Highest Price' => number_format($highestPriceDto->price, 8),
                'Highest Price Exchange' => $highestPriceDto->exchange,
                'Profit %' => number_format($profitPercentage, 2) . '%',
            ];
        }

        usort($tableData, function($a, $b) {
            return $b['Profit %'] <=> $a['Profit %'];
        });

        if (!count($tableData)) {
            $this->info('No arbitrage opportunities found for common currency pairs across exchanges.');
            return;
        }
        $this->table(['Currency Pair', 'Lowest Price', 'Lowest Price Exchange', 'Highest Price', 'Highest Price Exchange', 'Profit %'], $tableData);
    }
}
