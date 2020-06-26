<?php
declare(strict_types = 1);

namespace Dobreff\Account;

use Dobreff\Money\Transaction;

/**
 * Class that holds the single account data
 *
 */
class Account
{
    /**
     * Account ID
     *
     * @access private
     * @var int
     *
     */
    private $id;

    /**
     * Account Wallet with all the money
     *
     * @access private
     * @var Dobreff\Account\Wallet
     *
     */
    private $wallet;

    /**
     * Type of the account: natural or legal
     *
     * @access private
     * @var $type
     */
    private $type;

    /**
     * Valid account types
     *
     * @access private
     * @var TYPES
     */
    private const TYPES = [
      'natural' => 'natural',
      'legal' => 'legal',
      'juridical' => 'juridical',
    ];

    /**
     * Class constructor
     *
     * @param int $id
     * @param Dobreff\Account\Wallet $wallet
     * @param string $type
     */
    public function __construct(int $id, Wallet $wallet, string $type)
    {
        if (!isset(self::TYPES[$type])) {
            throw new \InvalidArgumentException('Unsupported account type: '.$type);
        }
        if (!($id > 0)) {
            throw new \InvalidArgumentException('The ID of the account must be positive number');
        }
        $this->id = $id;
        $this->type = $type;
        $this->wallet = $wallet;

        $this->transaction = new Transaction($this);

        $this->wallet->setParent($this);
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getTransaction() : Transaction
    {
        return $this->transaction;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getTypes() : array
    {
        return self::TYPES;
    }

    public function setType(string $string) : void
    {
        if (!isset(self::TYPES[$type])) {
            throw new \InvalidArgumentException('Unsupported account type: '.$type);
        }
        $this->type = $type;
    }

    public function getWallet() : Wallet
    {
        return $this->wallet;
    }
}
