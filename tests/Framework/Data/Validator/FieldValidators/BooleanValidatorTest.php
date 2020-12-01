<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator\FieldValidators;

use PHPUnit\Framework\TestCase;

/**
 * Class BooleanValidatorTest
 * @covers  \DailyTasks\Framework\Data\Validator\FieldValidators\BooleanValidator
 * @package Framework\Data\Validator\FieldValidators
 */
class BooleanValidatorTest extends TestCase
{
    public function testValidate()
    {
        $validator = new BooleanValidator();
        $this->assertTrue($validator->validate(true));
        $this->assertTrue($validator->validate(false));
        $this->assertFalse($validator->validate(0));
        $this->assertFalse($validator->validate(1));
        $this->assertFalse($validator->validate('1'));
    }
}
