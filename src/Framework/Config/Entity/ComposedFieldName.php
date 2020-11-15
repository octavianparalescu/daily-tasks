<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config\Entity;


class ComposedFieldName
{
    private string $arrayNameOrFieldName;
    private ?string $fieldNameInArray;

    public function __construct(string $arrayNameOrFieldName, ?string $fieldNameInArray = null)
    {
        $this->arrayNameOrFieldName = $arrayNameOrFieldName;
        $this->fieldNameInArray = $fieldNameInArray;
    }

    /**
     * @return string
     */
    public function getArrayNameOrFieldName(): string
    {
        return $this->arrayNameOrFieldName;
    }

    /**
     * @return string|null
     */
    public function getFieldNameInArray(): ?string
    {
        return $this->fieldNameInArray;
    }
}