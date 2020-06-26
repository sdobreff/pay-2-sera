<?php
declare(strict_types = 1);

namespace Dobreff\Money;

use Dobreff\Components\Component;
use Dobreff\Account\Account;
use Dobreff\Money\{
	Converter, 
	MoneyFactory, 
	TransactionFees
};


/**
 * Creates the transaction and stores the values
 */
class Transaction extends Component
{
    /**
     * All the transactions for the account
     * @access private
     * @var array $transactions
     */
    private $transactions = [];

    /**
     * All the transactions by week (the amount is summed and in primary Currency - EUR)
     * Associative array - the week number (with year and transaction type) is used as key for fast access
     * @access private
     * @var array $transactions
     */
    private $transactionsByWeek = [];

    /**
     * Creates the object and assigns the parent
     */
    public function __construct(Account $account)
    {
        parent::setParent($account);
    }

    /**
     * Creates the transaction
     *
     * Stores the transaction in the transactions array for the account for history usage.
     * Stores the transaction in the transactionsByWeek array. Converts the given amount in the primary Currency - EUR,
     * sums the amount with previous value.
     *
     * @param array $transaction @see Dobreff\Reader\TransactionsReader
     *
     * @return void
     */
    public function addTransaction(array $transaction) : void
    {
        $this->transactions[] = [
            'date' 		=> $transaction['date'],
            'amount' 	=> $transaction['amount'],
            'currency' 	=> $transaction['currency'],
            'type' 		=> $transaction['type_operation'],
        ];

        # Converting the money and logs the transaction

        $currenciesList = $this->getParent()
                                ->getWallet()
                                ->getCurrenciesList();

        if (empty($currenciesList[$transaction['currency']])) {
        	throw new \ErrorException('Unsupported currency provided! \''.$transaction['currency'].'\'');
        }

        $accountTypes = $this->getParent()
                                ->getTypes();

        if (!isset($accountTypes[$transaction['type_customer']])) {
            throw new \InvalidArgumentException('Unsupported account type: '.$transaction['type_customer']);
        }

        $money = MoneyFactory::create(
                                        (float) $transaction['amount'],
                                        $currenciesList[$transaction['currency']]
                                    );

        $amount = Converter::convert($money, $currenciesList['EUR']);

        $date = new \DateTime($transaction['date']);

        $weekKey = $date->format('W').$date->format('Y').$transaction['type_operation'];

        if (isset($this->transactionsByWeek[$weekKey])) {
            $this->transactionsByWeek[$weekKey]['amount'] += $amount;
            $this->transactionsByWeek[$weekKey]['num_transactions']++;
        } else {
            $this->transactionsByWeek[$weekKey] = [
                'amount' => $amount,
                'num_transactions' => 1,
            ];
        }

        # End of thansaction loging

        # Start calculating fees and output the results to the STDOUT

        $transactionFees = new TransactionFees();
        
        $feeForTransaction = $transactionFees->calculateFee(
                                                        (int) $this->transactionsByWeek[$weekKey]['num_transactions'],
                                                        (float) $this->transactionsByWeek[$weekKey]['amount'],
                                                        $currenciesList,
                                                        $transaction
                                                    );

        fwrite(
            STDOUT,
            number_format(Calculate::round($feeForTransaction), 2, '.', '') ."\n"
        );

        # End Fee calculation

        # Sets the Money object amount in the wallet with the transaction

        $this->getParent()
                        ->getWallet()
                        ->getMoney()[$transaction['currency']]
                        ->doTransaction($transaction);
    }

    public function getTransactions() : array
    {
        return $this->transactions;
    }

    public function getTransactionsByWeek() : array
    {
        return $this->transactionsByWeek;
    }
}
