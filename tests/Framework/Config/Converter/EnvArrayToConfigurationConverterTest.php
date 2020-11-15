<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config\Converter;

use PHPUnit\Framework\TestCase;

class EnvArrayToConfigurationConverterTest extends TestCase
{
    public function testConvertArrayToConfiguration()
    {
        $array = [
            'TEST1' => 'TEST2',
            'TEST3_TEST4' => 'TEST5',
        ];

        $converter = new EnvArrayToConfigurationConverter(new ComposedFieldNameConverter());
        $configuration = [
            'TEST1' => 'TEST2',
            'TEST3' => [
                'TEST4' => 'TEST5'
            ]
        ];
        $this->assertEquals($configuration, $converter->convertArrayToConfiguration($array));
    }
}
