<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Dobreff\Account\{
    Account, 
    Wallet
};
use Dobreff\Money\{Transaction, Currencieslist};

final class AccountTest extends TestCase
{
    public function testCanBeCreatedValid(): void
    {
        $id = 1;
        $currencieslist = new Currencieslist();
        $wallet = new Wallet($currencieslist);
        $type = 'natural';
        $account = new Account($id, $wallet, $type);
        $this->assertInstanceOf(
            account::class,
            $account
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCanNotBeCreatedInvalidId(): void
    {
        $id = 0;
        $currencieslist = new Currencieslist();
        $wallet = new Wallet($currencieslist);
        $type = 'natural';
        $account = new Account($id, $wallet, $type);
        $this->assertInstanceOf(
            account::class,
            $account
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCanNotBeCreatedInvalidType(): void
    {
        $id = 0;
        $currencieslist = new Currencieslist();
        $wallet = new Wallet($currencieslist);
        $type = 'invalid';
        $account = new Account($id, $wallet, $type);
        $this->assertInstanceOf(
            account::class,
            $account
        );
    }
}
