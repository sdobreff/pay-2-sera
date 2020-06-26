<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Dobreff\Money\{
    Calculate,
    MoneyFactory,
    CurrencyFactory
};

final class CalculateTest extends TestCase
{
    public function testCanAdd(): void
    {
        $currency = currencyFactory::create('EUR', 1);
        $money = MoneyFactory::create(100, $currency);

        Calculate::add($money, 400);

        $this->assertEquals(
            500, 
            $money->getAmount()
        );
    }

    public function testCanSubtract(): void
    {
        $currency = currencyFactory::create('EUR', 1);
        $money = MoneyFactory::create(100, $currency);

        Calculate::subtract($money, 50);

        $this->assertEquals(
            50, 
            $money->getAmount()
        );
    }

    public function testCanRound(): void
    {
        $val = Calculate::round(1.71111);

        $this->assertEquals(
            1.72, 
            $val
        );
    }
}