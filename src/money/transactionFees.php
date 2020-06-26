<?php
declare(strict_types = 1);

namespace Dobreff\Money;

/**
 * Aplies the transaction fee for given transaction
 */
class TransactionFees
{
    /**
     * Stores the configuration array - with all the logic for the given transaction
     * @access private
     * @var array $config
     */
    private $config = [];

    /**
     * Default currency, to skip unnecessary logic
     * @access private
     * @var string $defaultCurrency
     */
    private $defaultCurrency = '';

    /**
     * Stores the path to the configuration file
     * @access private
     * @var array $config
     */
    private $iniConfig = __DIR__.'/../config.ini';

    /**
     * Creates the class and reads the configuration file
     */
    public function __construct()
    {
    	if ((is_file($this->iniConfig) === true) && (is_readable($this->iniConfig) === true)) {
        	$this->config = parse_ini_file($this->iniConfig, true);
        	if (!$this->config) {
        		throw new \ErrorException('Can not read ini configuration from '.$this->iniConfig);		
        	}
        	$this->defaultCurrency = $this->config['common']['default_currency'];
        } else {
        	throw new \ErrorException('The configuration file '.$this->iniConfig.' does not exist');
        }
    }

    /**
     * Calculates transaction fee
     *
     * @param int $weekTransaction - how many transactions are there for the given week
     * @param float $amountSum - the sum of the transactions for the week
     * @param CurrenciesList $currenciesList - currencies and convertion rates
     * @param string $transaction - current transaction array @see Dobreff\Reader\TransactionsReader
     *
     * @return float amount
     */
    public function calculateFee(
        int $weekTransaction,
        float $amountSum,
        CurrenciesList $currenciesList,
        array $transaction
        ) : float {

        $fee = 0;

        $accountType = ($transaction['type_customer'] == 'juridical')?'legal':$transaction['type_customer'];
        $amount = (float) $transaction['amount'];

        if ('cash_in' == $transaction['type_operation']) {
            $cacheRate = (float) $this->config[$accountType]['cash_in_rate'];
            $cacheFee = (float) $this->config[$accountType]['cash_in_max'];
            $maxFreeTransactions = (int) $this->config[$accountType]['max_operations_per_week_cash_in'];
            $maxFreeAmount = (float) $this->config[$accountType]['max_free_amount_cash_in'];
        } elseif ('cash_out' == $transaction['type_operation']) {
            $cacheRate = (float) $this->config[$accountType]['cash_out_rate'];
            $cacheFee = (float) $this->config[$accountType]['cash_out_min'];
            $maxFreeTransactions = (int) $this->config[$accountType]['max_operations_per_week_cash_out'];
            $maxFreeAmount = (float) $this->config[$accountType]['max_free_amount_cash_out'];
        } else {
            throw new \Exception('Unsupported opperation: '.$transaction['type_operation']);
        }

        if ($this->defaultCurrency != $transaction['currency']) {
	        # Converts the whole sum to current transaction currency
	        $money = MoneyFactory::create($cacheFee, $currenciesList['EUR']);
	        $cacheFee = Converter::convert($money, $currenciesList[$transaction['currency']]);
	    }


        # Applies the logic for the fee calculation

        if ( # Both - week cashe outs are <= 3 and amount is not exceeded
            $weekTransaction <= $maxFreeTransactions &&
            $amountSum <= $maxFreeAmount
            ) {
            return 0;
        }

        $feeForAmount = ($cacheRate / 100) * $amount; # pre calculate fee
        # Checks the maximum free of charge amount for given week (if any) and checks if the whole sum for the week is bigger than free of charge amount
        if ($maxFreeAmount > 0 && $amountSum > $maxFreeAmount && $weekTransaction <= $maxFreeTransactions) {

        	$wholeAmountInCurrency = $amountSum;
        	$freeAmountInCurrency = $maxFreeAmount;

        	if ($this->defaultCurrency != $transaction['currency']) {

	            # Converts the whole sum to current transaction currency
	            $money = MoneyFactory::create($amountSum, $currenciesList['EUR']);
	            $wholeAmountInCurrency = Converter::convert($money, $currenciesList[$transaction['currency']]);

	            # Converts the free of charge amount sum to current transaction currency
	            $money = MoneyFactory::create($maxFreeAmount, $currenciesList['EUR']);
	            $freeAmountInCurrency = Converter::convert($money, $currenciesList[$transaction['currency']]);
	        }

            # Calculates the fee
            if (($wholeAmountInCurrency - $amount) < $freeAmountInCurrency) {
                $feeForAmount = ($cacheRate / 100) * (($wholeAmountInCurrency - $freeAmountInCurrency));
            }
        }

        # Applies the fee percentage and calculates the sum
        if ('cash_in' == $transaction['type_operation']) {
            $fee = (($feeForAmount) > $cacheFee) ? $cacheFee : $feeForAmount;
        } elseif ('cash_out' == $transaction['type_operation']) {
            $fee = (($feeForAmount) < $cacheFee) ? $cacheFee : $feeForAmount;
        }

        return Calculate::round($fee);
    }

    public function getConfig() : array
    {
        return $this->config;
    }
}
