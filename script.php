#!/usr/bin/php
<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Dobreff\Reader\CurrencyReader;
use Dobreff\Reader\TransactionReader;
use Dobreff\Account\Wallet;
use Dobreff\Account\Accountslist;
use Dobreff\Account\AccountFactory;

if (ini_get('max_execution_time') !== 0) { # Dont trust the enviroment
    ini_set('max_execution_time', 0);
}

if (empty($argv) || count($argv) != 2) {
	helpInfo();
}

if ((is_file($argv[1]) === true) && (is_readable($argv[1]) === true)) {
    try {
        $currenciesList = CurrencyReader::readCurrencies(__DIR__ . '/data/currencies.csv');

        $wallet = new Wallet($currenciesList);
    } catch (\Throwable $t) {
        echo 'Can not process execution during errors: '.$t->getMessage();
        throw new \ErrorException('Data provided is invalid!');
    }

    try {
        $accounts = new Accountslist();
        $accounts->addAccount(AccountFactory::create(1, $wallet, 'natural'));
        $accounts->addAccount(AccountFactory::create(2, $wallet, 'legal'));
        $accounts->addAccount(AccountFactory::create(3, $wallet, 'natural'));
        foreach (TransactionReader::readTransactions($argv[1]) as $value) {
        	if (!empty($accounts[$value['id_customer']])) {
	            $accounts[$value['id_customer']]
	            ->getTransaction()
	            ->addTransaction($value);
	        } else {
	        	throw new \ErrorException('Non existing account ID provided - '.$value['id_customer']);
	        }
        }
    } catch (\Throwable $t) {
        echo 'Can not process transactions: '.$t->getMessage();
    }
} else {
    print 'File not found - '.$argv[1]."\n";
	helpInfo();
}

function helpInfo()
{
    print 'Please provide file name with data as first parameter!'."\n";
    print 'Example use:'."\n";
    print '\'php script.php input.csv\''."\n";

    exit();
}
