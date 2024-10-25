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
