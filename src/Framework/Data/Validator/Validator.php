<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator;


use DailyTasks\Framework\Application\Kernel;
use DailyTasks\Framework\Data\Exception;
use DailyTasks\Framework\Data\Validator\Contract\ValidatorInterface;
use DailyTasks\Framework\Data\Validator\Entity\ValidationError;
use DailyTasks\Framework\Data\Validator\FieldValidators\BooleanValidator;
use DailyTasks\Framework\Data\Validator\FieldValidators\DateTimeValidator;
use DailyTasks\Framework\Data\Validator\FieldValidators\IntValidator;
use DailyTasks\Framework\Data\Validator\FieldValidators\MaxStringLengthValidator;
use DailyTasks\Framework\Data\Validator\FieldValidators\StringValidator;
use DailyTasks\Framework\Data\Validator\Key\ValidationErrorKey;
use DailyTasks\Framework\Data\Validator\Map\ValidationErrorMap;

class Validator
{
    const VALIDATION_RULE_INT = 'int';
    const VALIDATION_RULE_BOOLEAN = 'boolean';
    const VALIDATION_RULE_STRING = 'string';
    const VALIDATION_RULE_LENGTH_UNDER = 'length-under';
    const VALIDATION_RULE_REQUIRED = 'required';
    const VALIDATION_RULE_DATETIME = 'datetime';
    const VALIDATION_PROPERTY_OF_ENTITY = 'not-property-of-entity';
    /**
     * @var IntValidator
     */
    private IntValidator $intValidator;
    /**
     * @var StringValidator
     */
    private StringValidator $stringValidator;
    /**
     * @var MaxStringLengthValidator
     */
    private MaxStringLengthValidator $maxStringLengthValidator;
    /**
     * @var BooleanValidator
     */
    private BooleanValidator $booleanValidator;
    /**
     * @var DateTimeValidator
     */
    private DateTimeValidator $dateTimeValidator;

    public function __construct(
        IntValidator $intValidator,
        StringValidator $stringValidator,
        MaxStringLengthValidator $maxStringLengthValidator,
        BooleanValidator $booleanValidator,
        DateTimeValidator $dateTimeValidator
    ) {
        $this->intValidator = $intValidator;
        $this->stringValidator = $stringValidator;
        $this->maxStringLengthValidator = $maxStringLengthValidator;
        $this->booleanValidator = $booleanValidator;
        $this->dateTimeValidator = $dateTimeValidator;
    }

    public function validate(ValidatorInterface $validator, array $entityData)
    {
        $validationRules = $validator->getRules();
        $validationErrors = new ValidationErrorMap();
        // Verify for fields against rules
        foreach ($entityData as $fieldName => $datum) {
            if ($this->verifyFieldIsPropertyOfEntity($validationRules, $fieldName, $validationErrors)) {
                $validationRulesForEntity = $validationRules[$fieldName];
                $this->validateField($fieldName, $datum, $validationRulesForEntity, $validationErrors);
            }
        }

        // Verify for rules against fields (eg. for the required rule)
        foreach ($validationRules as $fieldName => $rules) {
            if (in_array(self::VALIDATION_RULE_REQUIRED, $rules)) {
                if (!in_array($fieldName, array_keys($entityData))) {
                    $validationErrors->add(
                        new ValidationError(
                            new ValidationErrorKey($fieldName, self::VALIDATION_RULE_REQUIRED), 'Field is missing.'
                        )
                    );
                }
            }
        }

        return $validationErrors;
    }

    /**
     * @param array              $validationRules
     * @param string             $fieldName
     * @param ValidationErrorMap $validationErrors
     *
     * @return bool
     * @throws Exception
     */
    private function verifyFieldIsPropertyOfEntity(
        array $validationRules,
        string $fieldName,
        ValidationErrorMap $validationErrors
    ): bool {
        if (!in_array($fieldName, array_keys($validationRules))) {
            $validationErrors->add(
                new ValidationError(
                    new ValidationErrorKey($fieldName, self::VALIDATION_PROPERTY_OF_ENTITY),
                    'Field is not a property of the entity.'
                )
            );

            return false;
        }

        return true;
    }

    /**
     * @param string             $fieldName
     * @param                    $fieldValue
     * @param array              $validationRulesForEntity
     * @param ValidationErrorMap $errorMap
     *
     * @throws Exception
     */
    private function validateField(
        string $fieldName,
        $fieldValue,
        array $validationRulesForEntity,
        ValidationErrorMap $errorMap
    ) {
        foreach ($validationRulesForEntity as $key => $value) {
            // Simple rules
            if (is_numeric($key)) {
                switch ($value) {
                    case self::VALIDATION_RULE_STRING:
                        if (!$this->stringValidator->validate($fieldValue)) {
                            $errorMap->add(
                                new ValidationError(
                                    new ValidationErrorKey($fieldName, self::VALIDATION_RULE_STRING), 'Field is not a string.'
                                )
                            );
                        }
                    break;
                    case self::VALIDATION_RULE_INT:
                        if (!$this->intValidator->validate($fieldValue)) {
                            $errorMap->add(
                                new ValidationError(
                                    new ValidationErrorKey($fieldName, self::VALIDATION_RULE_INT), 'Field is not an integer.'
                                )
                            );
                        }
                    break;
                    case self::VALIDATION_RULE_BOOLEAN:
                        if (!$this->booleanValidator->validate($fieldValue)) {
                            $errorMap->add(
                                new ValidationError(
                                    new ValidationErrorKey($fieldName, self::VALIDATION_RULE_BOOLEAN), 'Field is not a boolean.'
                                )
                            );
                        }
                    break;
                    case self::VALIDATION_RULE_DATETIME:
                        if (!$this->dateTimeValidator->validate($fieldValue, ['format' => Kernel::FORMAT_DATE_TIME])) {
                            $errorMap->add(
                                new ValidationError(
                                    new ValidationErrorKey($fieldName, self::VALIDATION_RULE_DATETIME),
                                    'Field is not a dateTime in format YYYY-MM-DD HH:MM:SS.'
                                )
                            );
                        }
                    break;
                }
            } // Composed rules
            elseif ($key === self::VALIDATION_RULE_LENGTH_UNDER) {
                if (!$this->maxStringLengthValidator->validate($fieldValue, ['length' => $value])) {
                    $errorMap->add(
                        new ValidationError(
                            new ValidationErrorKey($fieldName, self::VALIDATION_RULE_LENGTH_UNDER),
                            "Field must be under $value characters."
                        )
                    );
                }
            } else {
                throw new Exception('Validation rule invalid: ' . $key . $value);
            }
        }
    }
}