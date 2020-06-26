<?php
declare(strict_types = 1);

namespace Dobreff\Money;

use Dobreff\Components\Component;

/**
 * Money class - holds the amount of sum for given currency and currency object
 *
 */
class Money extends Component
{

    /**
     * Curent ammont of a given currency
     * @access private
     * @var amount float
     */
    private $amount = 0;

    /**
     * @access private
     * @var currency \Dobreff\Money\Currency
     */
    private $currency;

    /**
     * Creates new money object with given amount for given currency
     *
     * @param float $amount
     * @param float $currency
     *
     * @return object Dobreff\Money\Currency
     */
    public function __construct(float $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * Executes the transaction based on transaction type
     * The array structure could be seen here @see Dobreff\Reader\TransactionsReader
     *
     * @param array $transaction
     *
     * @return void
     */
    public function doTransaction(array $transaction) : void
    {
        if ('cash_out' == $transaction['type_operation'] ) {
        	Calculate::subtract($this, (float) $transaction['amount']);
        } elseif ('cash_in' == $transaction['type_operation']) {
        	Calculate::add($this, (float) $transaction['amount']);
        } else {
            throw new \Exception('Not supported transaction: '.$transaction['type_operation']);
        }
    }

    /**
     * Gets the currency of the Money object
     *
     * @return Currency the current currency
     */
    public function getCurrency() : Currency
    {
        return $this->currency;
    }

    /**
     * Gets the currency of the Money object
     *
     * @return Currency the current currency
     */
    public function setCurrency(Currency $currency) : void
    {
        $this->currency = $currency;
    }

    /**
     * Gets the amount the Money object for the given currency
     *
     * @return float the current amount for the given currency
     */
    public function getAmount() : float
    {
        return $this->amount;
    }

    /**
     * Sets the amount the Money object for the givem currency
     *
     * @param float $amount
     * @param string $currency
     *
     * @return Money object
     */
    public function setAmount(float $amount) : Money
    {
        $this->amount = $amount;
        return $this;
    }
}
