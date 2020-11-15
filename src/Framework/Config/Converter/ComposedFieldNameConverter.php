<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config\Converter;


use DailyTasks\Framework\Config\Entity\ComposedFieldName;
use DailyTasks\Framework\Config\Exception;

class ComposedFieldNameConverter
{
    const DELIMITER_CONFIG_ITEM_NAME = '_';

    public function convertFromString(string $composedFieldName)
    {
        if (empty($composedFieldName)) {
            throw new Exception('Cannot use a config value using an empty field name.');
        }

        $parts = explode(self::DELIMITER_CONFIG_ITEM_NAME, $composedFieldName);

        if (isset($parts[1]) && empty($parts[1])) {
            throw new Exception(
                "Field name composed like an array missed the field key in the array: $composedFieldName."
            );
        }

        return new ComposedFieldName(...$parts);
    }
}