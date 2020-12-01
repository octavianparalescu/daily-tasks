<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator\FieldValidators;

use PHPUnit\Framework\TestCase;

/**
 * Class StringValidatorTest
 * @covers  \DailyTasks\Framework\Data\Validator\FieldValidators\StringValidator
 * @package Framework\Data\Validator\FieldValidators
 */
class StringValidatorTest extends TestCase
{
    public function testValidate()
    {
        $validator = new StringValidator();
        $this->assertTrue($validator->validate('x'));
        $this->assertTrue($validator->validate("x"));
        $this->assertFalse($validator->validate(1));
    }
}
