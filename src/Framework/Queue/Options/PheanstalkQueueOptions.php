<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue\Options;


use DailyTasks\Framework\Data\Immutable;

class PheanstalkQueueOptions implements Immutable
{
    private string $host;
    private int $port;
    private int $connectTimeout;

    /**
     * BeanstalkQueueOptions constructor.
     *
     * @param string $host
     * @param int    $port
     * @param int    $connectTimeout
     */
    public function __construct(string $host, int $port = 11300, int $connectTimeout = 10)
    {
        $this->host = $host;
        $this->port = $port;
        $this->connectTimeout = $connectTimeout;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return int
     */
    public function getConnectTimeout(): int
    {
        return $this->connectTimeout;
    }
}