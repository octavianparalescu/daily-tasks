<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config\Converter;


use DailyTasks\Framework\Config\Exception;

class EnvArrayToConfigurationConverter
{
    /**
     * @var ComposedFieldNameConverter
     */
    private ComposedFieldNameConverter $composedFieldNameConverter;

    public function __construct(ComposedFieldNameConverter $composedFieldNameConverter)
    {
        $this->composedFieldNameConverter = $composedFieldNameConverter;
    }

    /**
     * @param array $envArray
     *
     * @return array
     * @throws Exception
     */
    public function convertArrayToConfiguration(array $envArray): array
    {
        $envConfiguration = [];
        foreach ($envArray as $field => $value) {
            $composedFieldName = $this->composedFieldNameConverter->convertFromString($field);

            $arrayNameOrFieldName = $composedFieldName->getArrayNameOrFieldName();
            $fieldNameInArray = $composedFieldName->getFieldNameInArray();
            if ($fieldNameInArray) {
                // It's an array
                if (!isset($envConfiguration[$arrayNameOrFieldName])) {
                    $envConfiguration[$arrayNameOrFieldName] = [];
                }

                $envConfiguration[$arrayNameOrFieldName][$fieldNameInArray] = $value;
            } else {
                // It's a simple configuration
                $envConfiguration[$arrayNameOrFieldName] = $value;
            }
        }

        return $envConfiguration;
    }
}