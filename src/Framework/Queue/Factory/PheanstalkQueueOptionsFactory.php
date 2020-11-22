<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue\Factory;


use DailyTasks\Framework\Queue\Options\PheanstalkQueueOptions;

class PheanstalkQueueOptionsFactory
{
    public function fromConfigOptions(array $options)
    {
        $hostname = $options['hostname'] ?? '127.0.0.1';
        $port = (int) $options['port'] ?? 11300;
        $connectTimeout = (int) $options['connect-timeout'] ?? 10;

        return new PheanstalkQueueOptions($hostname, $port, $connectTimeout);
    }
}