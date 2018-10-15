<?php
namespace Makasim\PhpFpm;

use Interop\Queue\ConnectionFactory;
use Interop\Queue\Context;
use Interop\Queue\Queue;
use Interop\Queue\Topic;

class PhpFpmDestination implements Queue, Topic
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQueueName(): string
    {
        return $this->getName();
    }

    public function getTopicName(): string
    {
        return $this->getName();
    }
}