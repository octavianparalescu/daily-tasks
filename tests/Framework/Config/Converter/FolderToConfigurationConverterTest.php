<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config\Converter;

use DailyTasks\Framework\Config\Exception;
use PHPUnit\Framework\TestCase;

class FolderToConfigurationConverterTest extends TestCase
{
    private const TEST_FOLDER = __DIR__ . DIRECTORY_SEPARATOR . 'folder_to_cfg_files' . DIRECTORY_SEPARATOR . 'test_folder';
    private const TEST_FOLDER_BAD = __DIR__ . DIRECTORY_SEPARATOR . 'folder_to_cfg_files' . DIRECTORY_SEPARATOR . 'test_folder_bad';

    public function testConvertFromFolder()
    {
        $folderConverter = new FolderToConfigurationConverter();
        $config = $folderConverter->convertFromFolder(self::TEST_FOLDER);
        $this->assertEquals(
            [
                'test1' => 'test2',
                'test3' => 'test4',
                'test5' => [
                    'test6' => 'test7',
                ],
                'test8' => 'test9',
            ],
            $config
        );
    }

    public function testShouldThrowIfNotArray()
    {
        $folderConverter = new FolderToConfigurationConverter();
        try {
            $folderConverter->convertFromFolder(self::TEST_FOLDER_BAD);
            $this->fail();
        } catch (\Throwable $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
        }
    }

    public function testShouldThrowIfNotFolder()
    {
        $folderConverter = new FolderToConfigurationConverter();
        try {
            $folderConverter->convertFromFolder('does_not_exist');
            $this->fail();
        } catch (\Throwable $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
        }
    }
}
