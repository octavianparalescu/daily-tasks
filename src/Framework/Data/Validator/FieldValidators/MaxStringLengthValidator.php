<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator\FieldValidators;


use DailyTasks\Framework\Data\Validator\Contract\FieldValidatorInterface;

class MaxStringLengthValidator implements FieldValidatorInterface
{
    public function validate($field, ?array $validatorParameters = null)
    {
        return strlen($field) <= $validatorParameters['length'];
    }
}