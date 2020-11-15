<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config\Converter;


use DailyTasks\Framework\Config\Exception;

class FolderToConfigurationConverter
{
    public function convertFromFolder(string $folderPath): array
    {
        if (!is_dir($folderPath) || !is_readable($folderPath)) {
            throw new Exception(
                "Configuration directory is not a folder or can't be accessed: $folderPath."
            );
        }

        $mergedConfig = [];
        $directoryContentsWithoutDots = array_diff(scandir($folderPath), ['..', '.']);
        foreach ($directoryContentsWithoutDots as $fileName) {
            $filePath = $folderPath . DIRECTORY_SEPARATOR . $fileName;
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

            $mergedConfig = array_merge($mergedConfig, $fileConfigArray);
        }

        return $mergedConfig;
    }
}