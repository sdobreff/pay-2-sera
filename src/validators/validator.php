<?php
declare(strict_types = 1);

namespace Dobreff\Validators;

class Validator
{
    public static function validateInt(string $int) : bool
    {
        if (filter_var($int, FILTER_VALIDATE_INT) && $int > 0) {
            return true;
        }

        throw new \ErrorException('Invalid input data, expect int - '.$int.' given');
        
        return false;
    }

    public static function validateFloat(string $float) : bool
    {
        if (filter_var($float, FILTER_VALIDATE_FLOAT) && $float > 0) {
            return true;
        }

        throw new \ErrorException('Invalid input data, expect float - '.$float.' given');
        
        return false;
    }

    public static function validateString(string $string) : bool
    {
        if (preg_match('/^[A-Za-z0-9_-]+$/', $string) !== 0) {
            return true;
        }
        
        throw new \ErrorException('Invalid input data, expect string - '.$string.' given');

        return false;
    }

    public static function validateDate(string $date) : bool
    {
        $parsedData = date_parse_from_format('Y-m-d', $date);

        if (0 == $parsedData['error_count'] && checkdate($parsedData['month'], $parsedData['day'], $parsedData['year'])) {
            return true;
        }
        
        throw new \ErrorException('Invalid input data, expect date - '.$date.' given');

        return false;
    }
}
