<?php
declare(strict_types = 1);

namespace Dobreff\Money;

/**
 * The currency class - every currency is represented by its Code and Exchange rate
 */
class Currency
{
    /**
     * Currency Code
     *
     * @access private
     * @var string
     */
    private $currencyCode;

    /**
     * @access private
     * @var float
     */
    private $currencyExchangeRate;

    /**
     * Creates new currency
     *
     * @param string $currencyCode
     * @param float $exchangeRate
     */
    public function __construct(string $currencyCode, float $exchangeRate)
    {
        $this->currencyCode = $currencyCode;
        $this->currencyExchangeRate = $exchangeRate;
    }

    /**
     * Returns the currency code.
     *
     * @return string
     */
    public function getCode() : string
    {
        return $this->currencyCode;
    }

    /**
     * Returs currency rate
     *
     * @return float
     */
    public function getRate() : float
    {
        return $this->currencyExchangeRate;
    }

    /**
     * Change currency rate
     *
     * @return void
     */
    public function setRate(float $rate) : float
    {
        return $this->currencyExchangeRate = $rate;
    }
}
