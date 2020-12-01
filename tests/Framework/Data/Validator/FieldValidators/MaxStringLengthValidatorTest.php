<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator\FieldValidators;

use PHPUnit\Framework\TestCase;

/**
 * Class MaxStringLengthValidatorTest
 * @covers  \DailyTasks\Framework\Data\Validator\FieldValidators\MaxStringLengthValidator
 * @package Framework\Data\Validator\FieldValidators
 */
class MaxStringLengthValidatorTest extends TestCase
{
    public function testValidate()
    {
        $validator = new MaxStringLengthValidator();
        $this->assertTrue($validator->validate('abc', ['length' => 3]));
        $this->assertFalse($validator->validate('abc', ['length' => 2]));
    }
}
