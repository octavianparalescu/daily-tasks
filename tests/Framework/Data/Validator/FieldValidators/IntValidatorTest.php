<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator\FieldValidators;

use PHPUnit\Framework\TestCase;

/**
 * Class IntValidatorTest
 * @covers  \DailyTasks\Framework\Data\Validator\FieldValidators\IntValidator
 * @package Framework\Data\Validator\FieldValidators
 */
class IntValidatorTest extends TestCase
{
    public function testValidate()
    {
        $validator = new IntValidator();
        $this->assertTrue($validator->validate(2));
        $this->assertTrue($validator->validate(-2));
        $this->assertFalse($validator->validate('1'));
        $this->assertFalse($validator->validate(1.5));
        $this->assertFalse($validator->validate('\''));
    }
}
