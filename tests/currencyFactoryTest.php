<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Dobreff\Money\{
    CurrencyFactory,
    Currency
};

final class CurrencyFactoryTest extends TestCase
{
    public function testCannotBeCreatedWithoutArguments(): void
    {
        $this->expectException(ArgumentCountError::class);

        $currency = currencyFactory::create();
    }
    
    public function testCannotBeCreatedFromInvalidInput(): void
    {
        $this->expectException(TypeError::class);

        $currency = currencyFactory::create(1);
    }
    
    public function testCanBeCreatedFromValidInput(): void
    {
        $currency = currencyFactory::create('EUR', 1);
        
        $this->assertInstanceOf(
            currency::class,
            $currency
        );


    }
}
