<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Dobreff\Money\{
    Money,
    CurrencyFactory
};

final class MoneyTest extends TestCase
{
    public function testCanBeCreatedValid(): void
    {
        $currency = CurrencyFactory::create('EUR', 1);

        $money = new Money(100, $currency);

        $this->assertInstanceOf(
            money::class,
            $money
        );
    }

    public function testCanChangeCurrency(): void
    {
        $eur = CurrencyFactory::create('EUR', 1);

        $money = new Money(100, $eur);

        $usd = CurrencyFactory::create('USD', 1.145);

        $money->setCurrency($usd);

        $this->assertEquals(
            $usd,
            $money->getCurrency()
        );
    }

    public function testCanGetAmount(): void
    {
        $currency = CurrencyFactory::create('EUR', 1);

        $money = new Money(100, $currency);

        $this->assertEquals(
            100,
            $money->getAmount()
        );
    }

    public function testCanSetAmount(): void
    {
        $currency = CurrencyFactory::create('EUR', 1);

        $money = new Money(0, $currency);

        $money->setAmount(100);

        $this->assertEquals(
            100,
            $money->getAmount()
        );
    }

    public function testCanDoTransaction(): void
    {
        $currency = CurrencyFactory::create('EUR', 1);

        $money = new Money(100, $currency);

        $transaction = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'natural',
            'type_operation' => 'cash_in',
            'amount' => '200.00',
            'currency' => 'EUR',
        ];

        $money->doTransaction($transaction);

        $this->assertEquals(
            300,
            $money->getAmount()
        );
 
        $transaction = [
            'date' => '2016-01-05',
            'id_customer' => '1',
            'type_customer' => 'natural',
            'type_operation' => 'cash_out',
            'amount' => '400.00',
            'currency' => 'EUR',
        ];

        $money->doTransaction($transaction);

        $this->assertEquals(
            -100,
            $money->getAmount()
        );
   }

    public function testCanGetCurrency(): void
    {
        $currency = CurrencyFactory::create('EUR', 1);

        $money = new Money(100, $currency);

        $this->assertEquals(
            $currency,
            $money->getCurrency()
        );
    }

    public function testCannotBeCreatedWithoutArguments(): void
    {
        $this->expectException(ArgumentCountError::class);

        $money = new Money();
    }

    public function testCannotBeCreatedFromInvalidInput(): void
    {
        $this->expectException(TypeError::class);

        $money = new Money(1);
    }

}
