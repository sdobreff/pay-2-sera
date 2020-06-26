<?php
declare(strict_types = 1);

namespace Dobreff\Account;

use Dobreff\Money\{
    MoneyFactory, 
    Currencieslist
};
use Dobreff\Components\Component;

/**
 * Stores all the money objects
 *
 */
class Wallet extends Component
{

    /**
     * @access private
     * @var array Dobreff\Money\Money
     */
    private $money = [];

    /**
     * @access private
     * @var array Dobreff\Money\Currencieslist
     */
    private $currencieslist = [];

    /**
     * Class constructor
     *
     * @param Dobreff\Money\Currencieslist $currencieslist
     */
    public function __construct(Currencieslist $currencieslist)
    {
        $this->setCurrencies($currencieslist);
    }
 
    /**
     * Sets composite class for the currencies
     *
     * @param Object $parent
     */
    public function setParent(&$parent) : void
    {
        parent::setParent($parent);

        foreach ($this->money as $money) {
            $money->setParent($parent);
        }
    }

    public function setCurrencies(Currencieslist $currencieslist) : void
    {
        $this->currencieslist = $currencieslist;

        foreach ($currencieslist->getCurrencies() as $code => $currency) {
            if (isset($this->money[$code])) {
                $this->money[$code]->setCurrency($currency);
            } else {
                $this->money[$code] = MoneyFactory::create(0, $currency);
            }
        }
    }

    public function getMoney() : array
    {
        return $this->money;
    }

    public function getCurrenciesList() : Currencieslist
    {
        return $this->currencieslist;
    }
}
