<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Dobreff\Money\{
    Transaction,
    Currencieslist
};
use Dobreff\Account\{
    Account, 
    Wallet,
    AccountFactory,
    Accountslist
};

final class AccountListTest extends TestCase
{
    public function testCanAddAccountToList(): void
    {
        $id = 1;
        $currencieslist = new Currencieslist($id, $wallet, $type);
        $wallet = new Wallet($currencieslist);
        $type = 'natural';

        $account = accountFactory::create($id, $wallet, $type);

        $accountsList = new Accountslist();
        $accountsList->addAccount($account);
        
        $this->assertCount(1, $accountsList->getAccounts());
    }

    public function testCanGetAccount(): void
    {
        $id = 1;
        $currencieslist = new Currencieslist($id, $wallet, $type);
        $wallet = new Wallet($currencieslist);
        $type = 'natural';

        $account = accountFactory::create($id, $wallet, $type);

        $accountsList = new Accountslist();
        $accountsList->addAccount($account);
                
        $this->assertEquals($account, $accountsList['1']);
    }
}
