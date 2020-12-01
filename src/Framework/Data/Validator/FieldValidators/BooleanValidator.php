<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator\FieldValidators;


use DailyTasks\Framework\Data\Validator\Contract\FieldValidatorInterface;

class BooleanValidator implements FieldValidatorInterface
{
    public function validate($field, ?array $validatorParameters = null)
    {
        return is_bool($field);
    }
}