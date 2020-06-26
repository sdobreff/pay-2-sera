<?php
declare(strict_types = 1);

namespace Dobreff\Money;

/**
 * Coverts one currency to other currency
 *
 */
final class Converter
{
    /**
     * Accepts Money object with the amount we want to convert, and currency object to which rate we want to convert
     *
     * @param Money    $money
     * @param Dobreff\Money\Currency $currency
     *
     * @return float
     */
    public static function convert(Money $money, Currency $currency) : float
    {
        $rate = ($money->getCurrency()->getRate()
                 / $currency->getRate());

        $amount = $money->getAmount() / $rate;

        return $amount;
    }
}