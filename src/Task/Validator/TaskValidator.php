<?php
declare(strict_types=1);

namespace DailyTasks\Task\Validator;


use DailyTasks\Framework\Data\Validator\Contract\ValidatorInterface;
use DailyTasks\Framework\Data\Validator\Validator;

class TaskValidator implements ValidatorInterface
{
    public function getRules(): array
    {
        return [
            'id' => [Validator::VALIDATION_RULE_INT],
            'title' => [
                Validator::VALIDATION_RULE_REQUIRED,
                Validator::VALIDATION_RULE_STRING,
                Validator::VALIDATION_RULE_LENGTH_UNDER => 255,
            ],
            'description' => [
                Validator::VALIDATION_RULE_STRING,
            ],
            'due_date' => [
                Validator::VALIDATION_RULE_REQUIRED,
                Validator::VALIDATION_RULE_DATETIME,
            ],
            'completed' => [
                Validator::VALIDATION_RULE_BOOLEAN,
            ],
        ];
    }
}