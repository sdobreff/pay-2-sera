<?php
declare(strict_types = 1);

namespace Dobreff\Money;

/**
 * Class creates new currency objects
 *
 */
class CurrencyFactory
{

    /**
     * Creates new currency
     *
     * @param string $currencyCode
     * @param float $exchangeRate
     *
     * @return object Dobreff\Money\Currency
     */
    public static function create(string $currencyCode, float $exchangeRate) : Currency
    {
        return new Currency($currencyCode, $exchangeRate);
    }
}
