<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Dobreff\Account\{
    Account, 
    Wallet, 
    AccountFactory
};
use Dobreff\Money\{
    Transaction, 
    Currencieslist, 
    CurrencyFactory};

final class TransactionTest extends TestCase
{
    public function testCanBeCreatedValid(): void
    {
        $id = 1;
        $currencieslist = new Currencieslist();
        $wallet = new Wallet($currencieslist);
        $type = 'natural';
        $account = AccountFactory::create($id, $wallet, $type);

        $transaction = new Transaction($account);

        $this->assertInstanceOf(
            transaction::class,
            $transaction
        );
    }

    public function testCannotBeCreatedWithoutArguments(): void
    {
        $this->expectException(ArgumentCountError::class);

        $transaction = new Transaction();
    }

    public function testCannotBeCreatedFromInvalidInput(): void
    {
        $this->expectException(TypeError::class);

        $transaction = new Transaction(1);
    }

    public function testAddTransaction(): void
    {
        $id = 1;
        $eur = currencyFactory::create('EUR', 1);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);

        $wallet = new Wallet($currencieslist);
        $type = 'natural';
        $account = AccountFactory::create($id, $wallet, $type);

        $transaction = new Transaction($account);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'natural',
            'type_operation' => 'cash_out',
            'amount' => '400.00',
            'currency' => 'EUR',
        ];

        $transaction->addTransaction($transactionLine);

        $transactionStored = [
          '0' =>[
            'date' => '2016-01-05',
            'amount' => '400.00',
            'currency' => 'EUR',
            'type' => 'cash_out',
          ]
        ];

        $this->assertEquals(
            $transactionStored, 
            $transaction->getTransactions()
        );
    }
}
