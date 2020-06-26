<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Dobreff\Account\{Account, Wallet, AccountFactory};
use Dobreff\Money\Currencieslist;

final class AccountFactoryTest extends TestCase
{
    public function testCannotBeCreatedWithoutArguments(): void
    {
        $this->expectException(ArgumentCountError::class);

        $accountFactory = accountFactory::create();
    }
    
    public function testCannotBeCreatedFromInvalidInput(): void
    {
        $this->expectException(TypeError::class);

        $accountFactory = accountFactory::create(1);
    }

    public function testCanBeCreatedFromValidInput(): void
    {
        $id = 1;
        $currencieslist = new Currencieslist();
        $wallet = new Wallet($currencieslist);
        $type = 'natural';
        $account = AccountFactory::create($id, $wallet, $type);
        $this->assertInstanceOf(
            account::class,
            $account
        );
    }

}
