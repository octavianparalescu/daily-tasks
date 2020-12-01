<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator\Key;


use DailyTasks\Framework\Data\ComposedKey;
use DailyTasks\Framework\Data\StringableProperties;

class ValidationErrorKey implements ComposedKey
{
    use StringableProperties;

    private string $fieldName;
    private string $ruleName;

    public function __construct(string $fieldName, string $ruleName)
    {
        $this->fieldName = $fieldName;
        $this->ruleName = $ruleName;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @return string
     */
    public function getRuleName(): string
    {
        return $this->ruleName;
    }
}