<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config\Converter;


use DailyTasks\Framework\Config\Exception;

class FolderToConfigurationConverter
{
    /**
     * @var FileToConfigurationConverter
     */
    private FileToConfigurationConverter $fileToConfigurationConverter;

    public function __construct(FileToConfigurationConverter $fileToConfigurationConverter)
    {
        $this->fileToConfigurationConverter = $fileToConfigurationConverter;
    }

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
            $filePath = $folderPath . '/' . $fileName;
            $fileConfigArray = $this->fileToConfigurationConverter->convertFromFile($filePath);

            $mergedConfig = array_merge($mergedConfig, $fileConfigArray);
        }

        return $mergedConfig;
    }
}