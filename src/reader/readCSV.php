<?php
declare(strict_types = 1);

namespace Dobreff\Reader;

/**
 * CSV reader basic functionality
 *
 */
trait readCSV
{

    /**
     * Reads the CSV file, and yields the line
     *
     * @param string $fileName
     *
     * @return \Generator
     */
    public static function readfile(string $fileName) : \Generator
    {
        $autedectEndings = ini_get('auto_detect_line_endings');

        if (!is_file($fileName)) {
            throw new \ErrorException('File not found - '.$fileName);
        }

        if (!is_readable($fileName)) {
            throw new \ErrorException('Can not read file - '.$fileName);
        }

        if ($autedectEndings == '0') {
            ini_set('auto_detect_line_endings', '1');
        }

        $file = fopen($fileName, 'r');

        if (!$file) {
            throw new \Exception('File open failed - '.$fileName);
        }

        while (($line = fgetcsv($file)) !== false) {
            yield $line;
        }

        fclose($file);

        if ($autedectEndings == '0') {
            ini_set('auto_detect_line_endings', '1');
        } else { # returns the original value - that is case which should never happen
            ini_set('auto_detect_line_endings', '1');
        }
    }
}
