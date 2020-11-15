<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config\Converter;

use PHPUnit\Framework\TestCase;

class EnvFileToArrayConverterTest extends TestCase
{
    private const ENV_FILE = __DIR__ . DIRECTORY_SEPARATOR . 'env_to_array_files' . DIRECTORY_SEPARATOR . 'test.env';

    public function testConvertFile()
    {
        $converter = new EnvFileToArrayConverter();
        $config = $converter->convertFile(self::ENV_FILE);
        $this->assertEquals(
            [
                'TEST1' => 'TEST2',
                'TEST3' => 'TEST4',
            ],
            $config
        );
    }
}
