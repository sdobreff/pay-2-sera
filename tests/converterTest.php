<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Dobreff\Money\{
    CurrencyFactory,
    Currencieslist,
    MoneyFactory,
    Converter
};

final class ConverterTest extends TestCase
{
    public function testCanAddCurrencyToList(): void
    {
        $eur = currencyFactory::create('EUR', 1);
        $usd = currencyFactory::create('USD', 1.445);

        $money = MoneyFactory::create(100, $usd);

        $this->assertEquals(
            69.204152249135, Converter::convert($money, $eur)
        );

        $money = MoneyFactory::create(69.204152249135, $eur);

        $this->assertEquals(
            100.0000000000001, Converter::convert($money, $usd)
        );
    }
}
