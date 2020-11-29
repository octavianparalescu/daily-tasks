<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config\Converter;


use DailyTasks\Framework\Config\Exception;

class FileToConfigurationConverter
{
    /**
     * @param string $filePath
     *
     * @return array
     * @throws Exception
     */
    public function convertFromFile(string $filePath)
    {
        if (!is_readable($filePath)) {
            throw new Exception(
                "Default config file is not accessible: $filePath."
            );
        }
        /** @noinspection PhpIncludeInspection */
        $fileConfigArray = include($filePath);

        if (!is_array($fileConfigArray)) {
            throw new Exception(
                "Default config file is not returning an array: $filePath."
            );
        }

        return $fileConfigArray;
    }
}