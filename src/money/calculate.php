<?php
declare(strict_types = 1);

namespace Dobreff\Money;

/**
 * Performs calculations and rounding
 *
 */
class Calculate
{
    /**
     * @access private
     * @var float $precision
     */
    private static $precision = 0.01;

    /**
     * Adds sum to given money
     *
     * @param Dobreff\Money\Money $money
     * @param float $amount
     *
     * @return Dobreff\Money\Money
     */
    public static function add(Money &$money, float $amount) : Money
    {
    	$money->setAmount(self::ceil($money->getAmount() + $amount, self::$precision));
        return $money;
    }

    /**
     * Extracts sum from given money
     *
     * @param Dobreff\Money\Money $money
     * @param float $amount
     *
     * @return Dobreff\Money\Money
     */
    public static function subtract(Money $money, float $amount) : Money
    {
    	$money->setAmount(self::ceil($money->getAmount() - $amount, self::$precision));
        return $money;
    }

    /**
     * Rounds values
     *
     * @param float $amount
     *
     * @return float
     */
    public static function round(float $amount) : float
    {
        return self::ceil($amount, self::$precision);
    }

    /**
     * Rounds values to given precision
     *
     * @param float $amount
     * @param float $precision
     *
     * @return float
     */
    private static function ceil(float $amount, float $precision) : float
    {
        return ceil($amount / $precision) * $precision;
    }
}
