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
    TransactionFees, 
    Currencieslist, 
    CurrencyFactory};

final class TransactionFeeTest extends TestCase
{
    public function testCanBeCreatedValid(): void
    {

        $transactionFees = new TransactionFees();

        $this->assertInstanceOf(
            transactionFees::class,
            $transactionFees
        );
    }

    public function testCalculateFee(): void
    {
        $eur = currencyFactory::create('EUR', 1);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'natural',
            'type_operation' => 'cash_out',
            'amount' => '400.00',
            'currency' => 'EUR',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            1.2, 
            $transactionFees->calculateFee(
                1, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeNaturalUnder1000(): void
    {
        $eur = currencyFactory::create('EUR', 1);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'natural',
            'type_operation' => 'cash_out',
            'amount' => '400.00',
            'currency' => 'EUR',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            0, 
            $transactionFees->calculateFee(
                1, 
                999, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeCasheOutAfter3(): void
    {
        $eur = currencyFactory::create('EUR', 1);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'natural',
            'type_operation' => 'cash_out',
            'amount' => '600.00',
            'currency' => 'EUR',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            1.8, 
            $transactionFees->calculateFee(
                4, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeCasheIn(): void
    {
        $eur = currencyFactory::create('EUR', 1);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'natural',
            'type_operation' => 'cash_in',
            'amount' => '600.00',
            'currency' => 'EUR',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            0.18, 
            $transactionFees->calculateFee(
                4, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeCasheInLegal(): void
    {
        $eur = currencyFactory::create('EUR', 1);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'legal',
            'type_operation' => 'cash_in',
            'amount' => '600.00',
            'currency' => 'EUR',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            0.18, 
            $transactionFees->calculateFee(
                4, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeCasheOutLegal(): void
    {
        $eur = currencyFactory::create('EUR', 1);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'legal',
            'type_operation' => 'cash_out',
            'amount' => '500.00',
            'currency' => 'EUR',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            1.5, 
            $transactionFees->calculateFee(
                2, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeCasheInLegalNoMoreThan5(): void
    {
        $eur = currencyFactory::create('EUR', 1);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'legal',
            'type_operation' => 'cash_in',
            'amount' => '50000.00',
            'currency' => 'EUR',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            5, 
            $transactionFees->calculateFee(
                2, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeCasheInNaturalNoMoreThan5(): void
    {
        $eur = currencyFactory::create('EUR', 1);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'natural',
            'type_operation' => 'cash_in',
            'amount' => '50000.00',
            'currency' => 'EUR',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            5, 
            $transactionFees->calculateFee(
                2, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeCasheOutLegalNoLessThan50(): void
    {
        $eur = currencyFactory::create('EUR', 1);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'legal',
            'type_operation' => 'cash_out',
            'amount' => '50.00',
            'currency' => 'EUR',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            0.5, 
            $transactionFees->calculateFee(
                2, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeCasheOutLegalNoLessThan50DifferentCurrency(): void
    {
        $eur = currencyFactory::create('EUR', 1);
        $usd = currencyFactory::create('USD', 1.145);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
        $currencieslist->addCurrency($usd);
  
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'legal',
            'type_operation' => 'cash_out',
            'amount' => '50.00',
            'currency' => 'USD',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            0.58, 
            $transactionFees->calculateFee(
                2, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeDifferentCurrency(): void
    {
        $eur = currencyFactory::create('EUR', 1);
        $usd = currencyFactory::create('USD', 1.145);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
        $currencieslist->addCurrency($usd);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'natural',
            'type_operation' => 'cash_out',
            'amount' => '400.00',
            'currency' => 'USD',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            1.2, 
            $transactionFees->calculateFee(
                1, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeNaturalUnder1000DifferentCurrency(): void
    {
        $eur = currencyFactory::create('EUR', 1);
        $usd = currencyFactory::create('USD', 1.145);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
        $currencieslist->addCurrency($usd);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'natural',
            'type_operation' => 'cash_out',
            'amount' => '400.00',
            'currency' => 'USD',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            0, 
            $transactionFees->calculateFee(
                1, 
                999, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeCasheOutAfter3DifferentCurrency(): void
    {
        $eur = currencyFactory::create('EUR', 1);
        $usd = currencyFactory::create('USD', 1.145);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
        $currencieslist->addCurrency($usd);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'natural',
            'type_operation' => 'cash_out',
            'amount' => '600.00',
            'currency' => 'USD',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            1.8, 
            $transactionFees->calculateFee(
                4, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeCasheInDifferentCurrency(): void
    {
        $eur = currencyFactory::create('EUR', 1);
        $usd = currencyFactory::create('USD', 1.145);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
        $currencieslist->addCurrency($usd);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'natural',
            'type_operation' => 'cash_in',
            'amount' => '600.00',
            'currency' => 'USD',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            0.18, 
            $transactionFees->calculateFee(
                4, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeCasheInLegalDifferentCurrency(): void
    {
        $eur = currencyFactory::create('EUR', 1);
        $usd = currencyFactory::create('USD', 1.145);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
        $currencieslist->addCurrency($usd);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'legal',
            'type_operation' => 'cash_in',
            'amount' => '600.00',
            'currency' => 'USD',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            0.18, 
            $transactionFees->calculateFee(
                4, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeCasheOutLegalDifferentCurrency(): void
    {
        $eur = currencyFactory::create('EUR', 1);
        $usd = currencyFactory::create('USD', 1.145);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
        $currencieslist->addCurrency($usd);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'legal',
            'type_operation' => 'cash_out',
            'amount' => '500.00',
            'currency' => 'USD',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            1.5, 
            $transactionFees->calculateFee(
                2, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeCasheInLegalNoMoreThan5DifferentCurrency(): void
    {
        $eur = currencyFactory::create('EUR', 1);
        $usd = currencyFactory::create('USD', 1.145);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
        $currencieslist->addCurrency($usd);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'legal',
            'type_operation' => 'cash_in',
            'amount' => '50000.00',
            'currency' => 'USD',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            5.73, 
            $transactionFees->calculateFee(
                2, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }

    public function testCalculateFeeCasheInNaturalNoMoreThan5DifferentCurrency(): void
    {
        $eur = currencyFactory::create('EUR', 1);
        $usd = currencyFactory::create('USD', 1.145);

        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
        $currencieslist->addCurrency($usd);
 
        $transactionLine = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'natural',
            'type_operation' => 'cash_in',
            'amount' => '50000.00',
            'currency' => 'USD',
        ];

        $transactionFees = new TransactionFees();
        
        $this->assertEquals(
            5.73, 
            $transactionFees->calculateFee(
                2, 
                1400, 
                $currencieslist,
                $transactionLine
            )
        );
    }
}
