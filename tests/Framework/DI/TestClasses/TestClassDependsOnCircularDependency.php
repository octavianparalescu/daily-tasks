<?php
declare(strict_types=1);

namespace DailyTasks\Framework\DI\TestClasses;


class TestClassDependsOnCircularDependency
{
    public function __construct(TestClassCircular1 $circular1){}
}