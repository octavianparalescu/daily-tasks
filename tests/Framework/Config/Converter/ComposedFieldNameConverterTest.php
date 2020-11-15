<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config\Converter;

use DailyTasks\Framework\Config\Exception;
use PHPUnit\Framework\TestCase;

class ComposedFieldNameConverterTest extends TestCase
{
    public function testConvertArrayIdentFromString()
    {
        $converter = new ComposedFieldNameConverter();
        $result = $converter->convertFromString('test1_test2');
        $this->assertEquals('test1', $result->getArrayNameOrFieldName());
        $this->assertEquals('test2', $result->getFieldNameInArray());
    }

    public function testConvertSingleIdentFromString()
    {
        $converter = new ComposedFieldNameConverter();
        $result = $converter->convertFromString('test1');
        $this->assertEquals('test1', $result->getArrayNameOrFieldName());
        $this->assertNull($result->getFieldNameInArray());
    }

    public function testShouldThrowWhenFieldNameEmpty()
    {
        $converter = new ComposedFieldNameConverter();
        try {
            $result = $converter->convertFromString('');
            $this->fail();
        } catch (\Throwable $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
        }
    }

    public function testShouldThrowWhenArrayKeyEmpty()
    {
        $converter = new ComposedFieldNameConverter();
        try {
            $result = $converter->convertFromString('test_');
            $this->fail();
        } catch (\Throwable $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
        }
    }
}
