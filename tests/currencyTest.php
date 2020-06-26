<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Dobreff\Money\Currency;

final class CurrencyTest extends TestCase
{
    public function testCanBeCreatedValid(): void
    {
        $currency = new Currency('EUR', 1);

        $this->assertInstanceOf(
            currency::class,
            $currency
        );
    }

    public function testCannotBeCreatedWithoutArguments(): void
    {
        $this->expectException(ArgumentCountError::class);

        $currency = new Currency();
    }

    public function testCannotBeCreatedFromInvalidInput(): void
    {
        $this->expectException(TypeError::class);

        $currency = new Currency(1);
    }

    public function testCorrectExchangeRate(): void
    {
        $currency = new Currency('EUR', 1.4445);

        $this->assertEquals(
            1.4445, 
            $currency->getRate()
        );
    }

    public function testCorrectCurrencyCode(): void
    {
        $currency = new Currency('EUR', 1.4445);

        $this->assertEquals(
            'EUR', 
            $currency->getCode()
        );
    }
}
