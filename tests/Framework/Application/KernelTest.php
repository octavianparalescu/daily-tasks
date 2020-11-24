<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Application;

use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    public function testStart()
    {
        $app = new Kernel();
        $app->run();
        $this->addToAssertionCount(1);
    }
}
