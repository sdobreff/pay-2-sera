<?php
declare(strict_types = 1);

namespace Dobreff\Money;

/**
 * Stores all the currency objects
 * Implements \ArrayAccess for project simplicity
 */
class Currencieslist implements \ArrayAccess
{
    /**
     * @access private
     * @var array $currencies
     */
    private $currencies = [];

    public function addCurrency(Currency $currency) : void
    {
        $this->currencies[$currency->getCode()] = $currency;
    }

    public function getCurrencies() : array
    {
        return $this->currencies;
    }

    public function offsetExists($offset)
    {
        return $this->currencies[$offset] ?? false;
    }

    public function offsetUnset($offset)
    {
        unset($this->currencies[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->currencies[$offset] ?? null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->currencies[] = $value;
        } else {
            $this->currencies[$offset] = $value;
        }
    }
}
