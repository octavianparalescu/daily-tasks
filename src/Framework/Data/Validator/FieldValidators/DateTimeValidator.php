<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator\FieldValidators;


use DailyTasks\Framework\Data\Validator\Contract\FieldValidatorInterface;
use DateTime;

class DateTimeValidator implements FieldValidatorInterface
{
    /**
     * Thanks, glavic
     * https://www.php.net/manual/ro/function.checkdate.php#113205
     *
     * @param            $field
     * @param array|null $validatorParameters
     *
     * @return bool
     */
    public function validate($field, ?array $validatorParameters = null)
    {
        $d = DateTime::createFromFormat($validatorParameters['format'], $field);

        return ($d && $d->format($validatorParameters['format']) === $field);
    }
}