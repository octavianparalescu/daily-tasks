<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator\Contract;


interface FieldValidatorInterface
{
    public function validate($field, ?array $validatorParameters = null);
}