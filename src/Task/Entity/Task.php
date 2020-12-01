<?php
declare(strict_types=1);

namespace DailyTasks\Task\Entity;


use DailyTasks\Framework\Data\Identifiable;
use DailyTasks\Framework\PersistentDatabase\Contract\DbObject;
use DateTime;

/**
 * Class Task
 * @codeCoverageIgnore
 * @package DailyTasks\Task\Entity
 */
class Task implements Identifiable, DbObject
{
    private ?int $id = null;
    private string $title;
    private string $description;
    private bool $completed;
    private DateTime $dueDate;

    public function __construct(
        ?int $id,
        string $title,
        string $description,
        bool $completed,
        DateTime $dueDate
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->completed = $completed;
        $this->dueDate = $dueDate;
    }

    /**
     * @return int
     */
    public function getKey()
    {
        return $this->getId();
    }

    /**
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * @return DateTime
     */
    public function getDueDate(): DateTime
    {
        return $this->dueDate;
    }
}