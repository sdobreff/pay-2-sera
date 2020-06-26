<?php
declare(strict_types = 1);

namespace Dobreff\Money;

/**
 * Class creates new money objects
 *
 */
class MoneyFactory
{

    /**
     * Creates new money
     *
     * @param float $amount
     * @param float $currency
     *
     * @return object Dobreff\Money\Money
     */
    public static function create(float $amount, Currency $currency) : Money
    {
        return new Money($amount, $currency);
    }
}
