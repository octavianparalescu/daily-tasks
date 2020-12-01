<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Application;

use DailyTasks\Framework\Exception;
use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    /**
     * @throws \DailyTasks\Framework\Config\Exception
     * @throws \DailyTasks\Framework\DI\Exception
     * @throws Exception
     * @runInSeparateProcess To prevent polluting the namespace
     */
    public function testStart()
    {
        putenv('ENV=dev');
        $app = new Kernel();
        $app->run();
        $this->addToAssertionCount(1);
    }
}
