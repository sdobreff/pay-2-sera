<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Dobreff\Validators\Validator;

final class ValidatorTest extends TestCase
{
    public function testCanValidateInt(): void
    {
        $this->assertTrue(
            Validator::validateInt('1')
        );
    }

    public function testCannotValidateInt(): void
    {
        $this->expectException(ErrorException::class);

        Validator::validateInt('1.3');
    }

    public function testCanValidateFloat(): void
    {
        $this->assertTrue(
            Validator::validateFloat('1.0')
        );
    }

    public function testCannotValidateFloat(): void
    {
        $this->expectException(ErrorException::class);

        Validator::validateFloat('1.3.9');
    }

    public function testCanValidateString(): void
    {
        $this->assertTrue(
            Validator::validateString('Usd_-123432')
        );
    }

    public function testCannotValidateString(): void
    {
        $this->expectException(ErrorException::class);

        Validator::validateString('eur//#@$');
    }

    public function testCanValidateDate(): void
    {
        $this->assertTrue(
            Validator::validateDate('2015-12-21')
        );
    }

    public function testCannotValidateDate(): void
    {
        $this->expectException(ErrorException::class);

        Validator::validateDate('2015-31-31');
    }
}
