## Install project
- Copy cp .env.example .env

Run the following commands

```bash
composer install
php artisan key:generate
php artisan migrate
```

# CryptoArbitrage

CryptoArbitrage is a project that allows you to analyze cryptocurrency prices across various exchanges.

## Using the Currency Pair Price Check Command

This command retrieves the lowest and highest prices for a specified currency pair across multiple cryptocurrency exchanges.

### Command Syntax

```bash
php artisan app:check-cur-pair-price {base?} {quote?}
```

# Usage Examples
## Entering both currencies manually:
```bash
php artisan app:check-cur-pair-price
```
The command will prompt you to enter the base and quote currencies.

## Entering base and quote currencies directly:
```bash
php artisan app:check-cur-pair-price BTC USDT
```
In this case, the command will directly retrieve prices for the currency pair BTC/USDT from all supported exchanges.

# Example Output
After executing the command, a table with prices will be displayed in the console, looking something like this:
```
Symbol: BTC/USDT
+-----------+-----------------+
| Exchange  | Price           |
+-----------+-----------------+
| Binance   | 49995.00        |
| Bybit     | 50005.00        |
| Poloniex  | 50007.00        |
| JBex      | 50010.00        |
| Whitebit  | 50020.00        |
+-----------+-----------------+
```

## Calculate Arbitrage Profit Command

The `app:calculate-arbitrage-profit` command calculates the potential arbitrage profit opportunities for common currency pairs across multiple cryptocurrency exchanges. It finds the lowest price for a currency pair on one exchange and the highest price on another exchange, calculating the profit percentage that can be achieved by buying low and selling high.

### Usage

To run the command, use the following syntax in your terminal:

```bash
php artisan app:calculate-arbitrage-profit
```

# Example Output
After executing the command, a table will be displayed in the console, looking something like this:
```
+---------------+-----------------+-----------------------+-----------------+------------------------+----------+
| Currency Pair | Lowest Price    | Lowest Price Exchange | Highest Price   | Highest Price Exchange | Profit % |
+---------------+-----------------+-----------------------+-----------------+------------------------+----------+
| ENSUSDT       | 15.86000000     | Poloniex              | 27.40000000     | JBEX                   | 72.76%   |
| SUSHIUSDT     | 0.68650000      | Poloniex              | 0.73400000      | Binance                | 6.92%    |
| SSVUSDT       | 21.24000000     | Bybit                 | 22.46000000     | Poloniex               | 5.74%    |
| DOTUSDT       | 4.15500000      | Poloniex              | 5.54400000      | JBEX                   | 33.43%   |
| DAIUSDT       | 0.98075000      | Poloniex              | 1.01890000      | Binance                | 3.89%    |
| PEOPLEUSDT    | 0.06939000      | Poloniex              | 0.07934100      | Whitebit               | 14.34%   |
| APTUSDT       | 9.85810000      | JBEX                  | 9.98900000      | Poloniex               | 1.33%    |
| DYDXUSDT      | 1.06600000      | JBEX                  | 1.08000000      | Poloniex               | 1.31%    |
| PEPEUSDT      | 0.00000959      | Poloniex              | 0.00000962      | Whitebit               | 0.35%    |
| SUIUSDT       | 1.92450000      | Bybit                 | 1.92970000      | Poloniex               | 0.27%    |
| UNIUSDT       | 7.84400000      | JBEX                  | 7.86200000      | Binance                | 0.23%    |
| FILUSDT       | 3.68400000      | Poloniex              | 3.69060000      | Whitebit               | 0.18%    |
| OPUSDT        | 1.70700000      | Poloniex              | 1.70960000      | Bybit                  | 0.15%    |
| ETHUSDT       | 2,507.79000000  | JBEX                  | 2,511.05000000  | Whitebit               | 0.13%    |
| AVAXUSDT      | 26.36000000     | JBEX                  | 26.39500000     | Poloniex               | 0.13%    |
| ARBUSDT       | 0.54900000      | JBEX                  | 0.54970000      | Poloniex               | 0.13%    |
| SANDUSDT      | 0.26038900      | Whitebit              | 0.26070000      | JBEX                   | 0.12%    |
| NEARUSDT      | 4.57200000      | JBEX                  | 4.57670000      | Bybit                  | 0.10%    |
| TRXUSDT       | 0.16525100      | Whitebit              | 0.16540000      | Binance                | 0.09%    |
| SHIBUSDT      | 0.00001767      | Poloniex              | 0.00001768      | Binance                | 0.08%    |
| XRPUSDT       | 0.52400000      | JBEX                  | 0.52430000      | Poloniex               | 0.06%    |
| DOGEUSDT      | 0.13801000      | JBEX                  | 0.13806110      | Whitebit               | 0.04%    |
| BTCUSDT       | 67,697.27000000 | Poloniex              | 67,710.60000000 | Bybit                  | 0.02%    |
+---------------+-----------------+-----------------------+-----------------+------------------------+----------+

```
