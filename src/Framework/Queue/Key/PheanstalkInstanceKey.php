<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue\Key;


use DailyTasks\Framework\Data\ComposedKey;
use DailyTasks\Framework\Data\Traits\StringableProperties;

class PheanstalkInstanceKey implements ComposedKey
{
    use StringableProperties;

    private string $hostname;
    private int $port;
    private int $connectTimeout;

    /**
     * PheanstalkInstanceKey constructor.
     *
     * @param string $hostname
     * @param int    $port
     * @param int    $connectTimeout
     */
    public function __construct(string $hostname, int $port, int $connectTimeout)
    {
        $this->hostname = $hostname;
        $this->port = $port;
        $this->connectTimeout = $connectTimeout;
    }
}