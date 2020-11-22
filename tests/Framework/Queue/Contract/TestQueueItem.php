<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue\Contract;


class TestQueueItem extends QueueItem
{
    private string $testString;
    private array $testArray;

    public function __construct(string $testString, array $testArray)
    {
        $this->testString = $testString;
        $this->testArray = $testArray;
    }

    /**
     * @return string
     */
    public function getTestString(): string
    {
        return $this->testString;
    }

    /**
     * @return array
     */
    public function getTestArray(): array
    {
        return $this->testArray;
    }
}