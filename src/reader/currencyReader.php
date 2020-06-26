<?php
declare(strict_types = 1);

namespace Dobreff\Reader;

use Dobreff\Money\{
    Currencieslist, 
    CurrencyFactory
};
use Dobreff\Validators\Validator;

/**
 * Reads the currencies and their exchange rates
 *
 */
class CurrencyReader
{
    use readCSV;

    /**
    * The array keys for the currencies. Values storing the name of the validation method to test against
    * @access private
    * @var CURRENCIES array
    */
    private const CURRENCIES = [
      'name' => 'string',
      'exchange_rate' => 'float',
    ];

    /**
     * Reads the currencies file, and creates the associative array
     *
     * @throws \ErrorException
     *
     * @param string $fileName
     *
     * @return Dobreff\Money\Currencieslist
     */
    public static function readCurrencies(string $fileName) : Currencieslist
    {
        $currencies = new Currencieslist();
        try {
            foreach (self::readfile($fileName) as $currency) {
                set_error_handler(function ($errno, $errstr, $errfile, $errline) {
                    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
                });

                try {
                    $lineCurrency = array_combine(array_keys(self::CURRENCIES), $currency);
                    if (!empty($lineCurrency)) {
                        try {
                            array_map(function ($readVal, $validateAgainst) {
                                $validateMethod = 'validate'.ucfirst($validateAgainst);
                                Validator::$validateMethod($readVal);
                            }, $lineCurrency, self::CURRENCIES);

                            $currencies->addCurrency(CurrencyFactory::create($lineCurrency['name'], (float) $lineCurrency['exchange_rate']));
                        } catch (\Throwable $t) {
                            echo 'Problem with validation: '.$t->getMessage();
                        }
                    }
                } catch (\Throwable $t) {
                    echo 'Problem with reading lines: '.$t->getMessage();
                }

                restore_error_handler();
            }
        } catch (\Throwable $t) {
            echo $t->getMessage();
        }

        if (0 == count($currencies->getCurrencies())) {
            throw new \ErrorException('No currencies provided');
        }

        return $currencies;
    }
}
