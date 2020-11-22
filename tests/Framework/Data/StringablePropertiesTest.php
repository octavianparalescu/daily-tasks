<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data;

use DailyTasks\Framework\Data\Traits\StringableProperties;
use PHPUnit\Framework\TestCase;

class StringablePropertiesTest extends TestCase
{
    public function testStringableProperties()
    {
        $object = new class('test3', 'test4') {
            use StringableProperties;

            private string $test1;
            private string $test2;

            public function __construct($test1, $test2)
            {
                $this->test1 = $test1;
                $this->test2 = $test2;
            }
        };
        $this->assertEquals('test1-test3;test2-test4', $object->__toString());
        $this->assertEquals('test1-test3;test2-test4', (string) $object);
    }
}
