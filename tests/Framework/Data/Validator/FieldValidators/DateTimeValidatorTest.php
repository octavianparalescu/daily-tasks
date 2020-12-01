<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator\FieldValidators;

use PHPUnit\Framework\TestCase;

/**
 * Class DateTimeValidatorTest
 * @covers  \DailyTasks\Framework\Data\Validator\FieldValidators\DateTimeValidator
 * @package Framework\Data\Validator\FieldValidators
 */
class DateTimeValidatorTest extends TestCase
{
    public function testValidate()
    {
        $validator = new DateTimeValidator();
        $this->assertTrue($validator->validate('1993-01-31', ['format' => 'Y-m-d']));
        $this->assertTrue($validator->validate('1993-01-31 09:37:45', ['format' => 'Y-m-d H:i:s']));
        $this->assertFalse($validator->validate('1993-01-31 23:37:45', ['format' => 'Y-m-d h:i:s']));
    }
}
