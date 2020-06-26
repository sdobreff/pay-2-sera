<?php
declare(strict_types = 1);

namespace Dobreff\Reader;

use Dobreff\Validators\Validator;

/**
 * Reads and transactions
 *
 */
class TransactionReader
{
    use readCSV;

    /**
    * The array keys for the transactions. Values storing the name of the validation method to test against
    * @access private
    * @var OPERATIONS array
    */
    private const OPERATIONS = [
      'date' => 'date',
      'id_customer' => 'int',
      'type_customer' => 'string',
      'type_operation' => 'string',
      'amount' => 'float',
      'currency' => 'string',
    ];

    /**
     * Reads the currencies file, and creates the associative array
     *
     * @throws \ErrorException
     *
     * @param string $fileName
     *
     * @return \Generator
     */
    public static function readTransactions(string $fileName) : \Generator
    {
        try {
            foreach (self::readfile($fileName) as $operation) {
                set_error_handler(function ($errno, $errstr, $errfile, $errline) {
                    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
                });

                try {
                    $lineOperation = array_combine(array_keys(self::OPERATIONS), $operation);
                    if (!empty($lineOperation)) {
                        try {
                            array_map(function ($readVal, $validateAgainst) {
                                $validateMethod = 'validate'.ucfirst($validateAgainst);
                                Validator::$validateMethod($readVal);
                            }, $lineOperation, self::OPERATIONS);

                            yield ($lineOperation);
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
            echo 'Problem with input: '.$t->getMessage();
            throw new \ErrorException('Cannot process the transaction file', 0);
        }
    }
}
