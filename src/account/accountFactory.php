<?php
declare(strict_types = 1);

namespace Dobreff\Account;

use Dobreff\Account\Wallet;

/**
 * Class that creates new account
 *
 */
class AccountFactory
{

    /**
     * Creates new Account
     *
     * @param int $id
     * @param Dobreff\Account\Wallet $wallet
     * @param string $type
     *
     * @return object Dobreff\Money\Account
     */
    public static function create(int $id, Wallet $wallet, string $type) : Account
    {
        return new Account($id, $wallet, $type);
    }
}
