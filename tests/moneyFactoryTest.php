<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Dobreff\Money\{
    CurrencyFactory,
    MoneyFactory,
    Money
};

final class MoneyFactoryTest extends TestCase
{
    public function testCannotBeCreatedWithoutArguments(): void
    {
        $this->expectException(ArgumentCountError::class);

        $currency = moneyFactory::create();
    }
    
    public function testCannotBeCreatedFromInvalidInput(): void
    {
        $this->expectException(TypeError::class);

        $currency = moneyFactory::create(1);
    }
    
    public function testCanBeCreatedFromValidInput(): void
    {
        $currency = currencyFactory::create('EUR', 1);
        $money = MoneyFactory::create(100, $currency);

        $this->assertInstanceOf(
            money::class,
            $money
        );

    }
}
