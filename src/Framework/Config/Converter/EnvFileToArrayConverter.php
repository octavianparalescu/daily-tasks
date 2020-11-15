<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config\Converter;


use DailyTasks\Framework\Config\Exception;
use M1\Env\Parser;
use Throwable;

class EnvFileToArrayConverter
{
    /**
     * @param string $filePath
     *
     * @return array
     * @throws Exception
     */
    public function convertFile(string $filePath): array
    {
        if (!is_file($filePath)) {
            // If there is no file created, we can safely use the default configuration
            return [];
        }
        if (!is_readable($filePath)) {
            // If the env file exists but is not readable means there is something wrong and we throw
            throw new Exception("Config file path is not readable: ${filePath}");
        }

        return $this->convertString(file_get_contents($filePath));
    }

    /**
     * @param string $fileContents
     *
     * @return array|null
     * @throws Exception
     */
    public function convertString(string $fileContents): array
    {
        try {
            $result = Parser::parse($fileContents);
        } catch (Throwable $exception) {
            throw new Exception("Env file parsing exception: " . $exception->getMessage(), 0, $exception);
        }

        return $result;
    }
}