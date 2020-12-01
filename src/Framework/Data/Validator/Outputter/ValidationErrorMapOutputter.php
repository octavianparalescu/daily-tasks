<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator\Outputter;


use DailyTasks\Framework\Data\Validator\Entity\ValidationError;
use DailyTasks\Framework\Data\Validator\Map\ValidationErrorMap;

trait ValidationErrorMapOutputter
{
    public function outputValidationErrorsToJson(ValidationErrorMap $errorMap): string
    {
        $output = [];
        /** @var ValidationError $error */
        foreach ($errorMap as $error) {
            $output[$error->getFieldName() . '-' . $error->getRuleName()] = $error->getError();
        }

        return json_encode($output);
    }
}