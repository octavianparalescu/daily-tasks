<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator\Entity;


use DailyTasks\Framework\Data\Identifiable;
use DailyTasks\Framework\Data\Validator\Key\ValidationErrorKey;

class ValidationError implements Identifiable
{
    private string $error;
    /**
     * @var ValidationErrorKey
     */
    private ValidationErrorKey $key;

    public function __construct(ValidationErrorKey $key, string $error)
    {
        $this->error = $error;
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->getKey()
                    ->getFieldName();
    }

    /**
     * @return string
     */
    public function getRuleName(): string
    {
        return $this->getKey()
                    ->getRuleName();
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    public function getKey()
    {
        return $this->key;
    }
}