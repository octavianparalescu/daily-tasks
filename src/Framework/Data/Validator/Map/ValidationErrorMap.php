<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator\Map;


use DailyTasks\Framework\Data\MapEntity;
use DailyTasks\Framework\Data\Validator\Entity\ValidationError;

class ValidationErrorMap extends MapEntity
{
    public function getEntitiesType(): string
    {
        return ValidationError::class;
    }
}