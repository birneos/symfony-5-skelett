<?php


namespace App\Service;


use DateTimeZone;
use Doctrine\DBAL\Logging\SQLLogger;
use Monolog\Logger;

class LoggerFactory extends Logger implements SQLLogger
{

    public function __construct(string $name, array $handlers = [], array $processors = [], ?DateTimeZone $timezone = null)
    {
        parent::__construct($name, $handlers, $processors, $timezone);
    }

    public static function create()
    {
        switch($type)
        {

        }
    }

    public function startQuery($sql, ?array $params = null, ?array $types = null)
    {
        // TODO: Implement startQuery() method.
    }

    public function stopQuery()
    {
        // TODO: Implement stopQuery() method.
    }
}