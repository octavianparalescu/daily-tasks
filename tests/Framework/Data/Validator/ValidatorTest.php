<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator;

use DailyTasks\Framework\Data\Validator\Contract\ValidatorInterface;
use DailyTasks\Framework\Data\Validator\FieldValidators\BooleanValidator;
use DailyTasks\Framework\Data\Validator\FieldValidators\DateTimeValidator;
use DailyTasks\Framework\Data\Validator\FieldValidators\IntValidator;
use DailyTasks\Framework\Data\Validator\FieldValidators\MaxStringLengthValidator;
use DailyTasks\Framework\Data\Validator\FieldValidators\StringValidator;
use DailyTasks\Framework\Data\Validator\Key\ValidationErrorKey;
use PHPUnit\Framework\TestCase;

/**
 * Class ValidatorTest
 * @covers  \DailyTasks\Framework\Data\Validator\Validator
 * @uses    \DailyTasks\Framework\Data\MapEntity
 * @uses    \DailyTasks\Framework\Data\StringableProperties
 * @uses    \DailyTasks\Framework\Data\Validator\Entity\ValidationError
 * @uses    \DailyTasks\Framework\Data\Validator\Key\ValidationErrorKey
 * @uses    \DailyTasks\Framework\Data\Validator\Map\ValidationErrorMap
 * @package Framework\Data\Validator
 */
class ValidatorTest extends TestCase
{
    /**
     * @var Validator
     */
    private Validator $validator;

    public function setUp(): void
    {
        parent::setUp();
        $this->validator = new Validator(
            new IntValidator(),
            new StringValidator(),
            new MaxStringLengthValidator(),
            new BooleanValidator(),
            new DateTimeValidator()
        );
    }

    public function testValidateARule()
    {
        $data = [
            'test1' => 'test2',
        ];
        $this->assertEmpty(
            $this->validator->validate(
                new class implements ValidatorInterface {
                    public function getRules(): array
                    {
                        return [
                            'test1' => [Validator::VALIDATION_RULE_REQUIRED],
                        ];
                    }
                },
                $data
            )
        );
    }

    public function testValidateNotInEntity()
    {
        $data = [
            'test1' => 'test2',
        ];
        $validationErrorMap = $this->validator->validate(
            new class implements ValidatorInterface {
                public function getRules(): array
                {
                    return [
                        'test3' => [Validator::VALIDATION_RULE_REQUIRED],
                    ];
                }
            },
            $data
        );
        $this->assertNotEmpty($validationErrorMap);
        $this->assertNotNull(
            $validationErrorMap->getByKey(new ValidationErrorKey('test1', Validator::VALIDATION_PROPERTY_OF_ENTITY))
        );
    }
}
