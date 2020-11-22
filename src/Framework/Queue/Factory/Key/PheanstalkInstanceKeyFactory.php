<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue\Factory\Key;


use DailyTasks\Framework\Queue\Key\PheanstalkInstanceKey;
use DailyTasks\Framework\Queue\Options\PheanstalkQueueOptions;

class PheanstalkInstanceKeyFactory
{
    public static function fromOptions(PheanstalkQueueOptions $options)
    {
        return new PheanstalkInstanceKey($options->getHost(), $options->getPort(), $options->getConnectTimeout());
    }
}