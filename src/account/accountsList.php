<?php
declare(strict_types = 1);

namespace Dobreff\Account;

/**
 * Class for holding all the accounts
 * Implements \ArrayAccess for project simplicity
 */
class Accountslist implements \ArrayAccess
{
    /**
     * @access private
     * @var array object Dobreff\Money\Account
     */
    private $accounts = [];

    public function addAccount(Account $account) : void
    {
        $this->accounts[$account->getId()] = $account;
    }

    public function getAccounts() : array
    {
        return $this->accounts;
    }

    public function offsetExists($offset)
    {
        return $this->accounts[$offset] ?? false;
    }

    public function offsetUnset($offset)
    {
        unset($this->accounts[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->accounts[$offset] ?? null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->accounts[] = $value;
        } else {
            $this->accounts[$offset] = $value;
        }
    }
}
