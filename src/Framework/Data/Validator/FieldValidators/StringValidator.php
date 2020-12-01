<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator\FieldValidators;


use DailyTasks\Framework\Data\Validator\Contract\FieldValidatorInterface;

class StringValidator implements FieldValidatorInterface
{
    public function validate($field, ?array $validationParameters = null)
    {
        return is_string($field);
    }
}