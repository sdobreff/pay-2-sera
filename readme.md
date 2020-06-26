To set the enviroment you must:
- have php 7.1
- have composer
- unzip file and run 'composer install' within the project's root dir

To run it you must:
- you can run it with following command:
 <path_to_php>/php script.php <path_csv_file_with_transactions_data>

- or as a shell script if your php is in /usr/bin, make file executable first and run
./script.php <path_csv_file_with_transactions_data>

Short documentation of the project:

The testing transaction data file is in the ./data/ directory, but you can use another, as long as it is readable for the script.
The application expects csv file with the following format:
<currency_code>,<currency_exchange_rate>
also to be in that directory, and be named 'currencies.csv'
There is a configuration file in the ./src dir named 'config.ini'. It has 3 sections:
[common] (defines the default currency)
[natural] holds natural account fees and discounts
[legal] holds legal account fees and discounts
From there you can change the logic for the fees and when they could be applied.

To run the tests use the following command from within the project root dir:
./vendor/bin/phpunit

Some notes:
Project creates the accounts first (with ids 1-3, taken from the test data file provided) and then starts parsing the transaction file. There is no dynamic accounts creation (during transactions file reading and if there is an account with non-existing ID, the exception will be thrown).