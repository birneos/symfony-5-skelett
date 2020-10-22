<?php


namespace App\Service;


use Psr\Log\LoggerInterface;

class Greeting
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {

        $this->logger = $logger;
    }

    public function greet(string $name)
    {
        $this->logger->info("Hello $name");

        return "Hello $name";
    }
}