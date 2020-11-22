<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue\Entity;


use DailyTasks\Framework\Data\Identifiable;
use DailyTasks\Framework\Queue\Key\PheanstalkInstanceKey;
use Pheanstalk\Pheanstalk;

class PheanstalkInstance implements Identifiable
{
    private string $hostname;
    private int $port;
    private int $connectTimeout;
    /**
     * @var PheanstalkInstanceKey
     */
    private PheanstalkInstanceKey $key;
    private ?Pheanstalk $pheanstalk = null;

    /**
     * PheanstalkInstance constructor.
     *
     * @param PheanstalkInstanceKey $key
     * @param string                $hostname
     * @param int                   $port
     * @param int                   $connectTimeout
     */
    public function __construct(
        PheanstalkInstanceKey $key,
        string $hostname,
        int $port,
        int $connectTimeout
    ) {
        $this->hostname = $hostname;
        $this->port = $port;
        $this->connectTimeout = $connectTimeout;
        $this->key = $key;
    }

    /**
     * @return PheanstalkInstanceKey
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $queue
     * @param string $message
     */
    public function publish(string $queue, string $message): void
    {
        $pheanstalk = $this->getConnection();
        $pheanstalk->useTube($queue)
                   ->put(
                       $message
                   );
    }

    public function watch(string $queue): string
    {
        $pheanstalk = $this->getConnection();
        $pheanstalk->useTube($queue)
                   ->watch($queue);

        return $pheanstalk->reserve()
                          ->getData();
    }

    /**
     * @return Pheanstalk
     */
    private function getConnection()
    {
        if (!$this->pheanstalk) {
            $this->pheanstalk = Pheanstalk::create($this->hostname, $this->port, $this->connectTimeout);
        }

        return $this->pheanstalk;
    }
}