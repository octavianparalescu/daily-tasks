<?php
declare(strict_types=1);

namespace DailyTasks\Framework\DI\TestClasses;


class TestClassTwoLevelsDependencies
{
    /**
     * @var TestClassOneLevelDependency
     */
    public TestClassOneLevelDependency $classOneLevelDependency;

    public function __construct(TestClassOneLevelDependency $classOneLevelDependency, \stdClass $class)
    {
        $this->classOneLevelDependency = $classOneLevelDependency;
    }
}