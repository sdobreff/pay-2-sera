<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Dobreff\Money\{
    CurrencyFactory,
    Currencieslist
};

final class CurrencyListTest extends TestCase
{
    public function testCanAddCurrencyToList(): void
    {
        $eur = currencyFactory::create('EUR', 1);

        $currencyList = new Currencieslist();
        $currencyList->addCurrency($eur);
        
        $this->assertCount(1, $currencyList->getCurrencies());
    }

    public function testCanGetCurrency(): void
    {
        $eur = currencyFactory::create('EUR', 1);

        $currencyList = new Currencieslist();
        $currencyList->addCurrency($eur);
        
        $this->assertEquals($eur, $currencyList['EUR']);
    }
}
