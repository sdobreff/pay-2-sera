<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Dobreff\Account\Wallet;
use Dobreff\Money\{
    Currencieslist,
    CurrencyFactory
};

final class WalletTest extends TestCase
{
    public function testCanBeCreatedValid(): void
    {
        $currencieslist = new Currencieslist();
        $wallet = new Wallet($currencieslist);

        $this->assertInstanceOf(
            wallet::class,
            $wallet
        );
    }

    public function testCannotBeCreatedWithoutArguments(): void
    {
        $this->expectException(ArgumentCountError::class);

        $wallet = new Wallet();
    }

    public function testCannotBeCreatedFromInvalidInput(): void
    {
        $this->expectException(TypeError::class);

        $wallet = new Wallet(1);
    }

    public function testSettingParent(): void
    {
        $currencieslist = new Currencieslist();
        $wallet = new Wallet($currencieslist);

        $wallet->setParent($currencieslist);

        $this->assertEquals(
            $currencieslist,
            $wallet->getParent()
        );
    }

    public function testCanChangeCurrencies(): void
    {
        $eur = CurrencyFactory::create('EUR', 1);
        $usd = CurrencyFactory::create('USD', 1.145);
        $currencieslist = new Currencieslist();
        $currencieslist->addCurrency($eur);
        $currencieslist->addCurrency($usd);

        $wallet = new Wallet($currencieslist);

        $this->assertEquals(
            $currencieslist,
            $wallet->getCurrenciesList()
        );

        $ypl = CurrencyFactory::create('YPL', 149);
        $gbh = CurrencyFactory::create('GBP', 0.95);

        $usd->setRate(1.565);
        $currencieslist->addCurrency($ypl);
        $currencieslist->addCurrency($gbh);

        $wallet->setCurrencies($currencieslist);
        
        $this->assertEquals(
            $currencieslist,
            $wallet->getCurrenciesList()
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSettingInvalidParent(): void
    {
        $currencieslist = new Currencieslist();
        $wallet = new Wallet($currencieslist);

        $test = 'some';

        $wallet->setParent($test);

        $this->assertEquals(
            $currencieslist,
            $wallet->getParent()
        );
    }
}
