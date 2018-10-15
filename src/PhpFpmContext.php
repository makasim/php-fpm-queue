<?php
namespace Makasim\PhpFpm;

use hollodotme\FastCGI\Client;
use Interop\Queue\ConnectionFactory;
use Interop\Queue\Consumer;
use Interop\Queue\Context;
use Interop\Queue\Destination;
use Interop\Queue\Exception\PurgeQueueNotSupportedException;
use Interop\Queue\Exception\SubscriptionConsumerNotSupportedException;
use Interop\Queue\Exception\TemporaryQueueNotSupportedException;
use Interop\Queue\Message;
use Interop\Queue\Producer;
use Interop\Queue\Queue;
use Interop\Queue\SubscriptionConsumer;
use Interop\Queue\Topic;

class PhpFpmContext implements Context
{
    /**
     * @var Client
     */
    private $cgiClient;

    public function __construct(Client $cgiClient)
    {
        $this->cgiClient = $cgiClient;
    }

    public function createMessage(string $body = '', array $properties = [], array $headers = []): Message
    {
        return new PhpFpmMessage($body, $properties, $headers);
    }

    public function createTopic(string $topicName): Topic
    {
        return new PhpFpmDestination($topicName);
    }

    public function createQueue(string $queueName): Queue
    {
        return new PhpFpmDestination($queueName);
    }

    public function createProducer(): Producer
    {
        return new PhpFpmProducer($this->cgiClient);
    }

    public function createConsumer(Destination $destination): Consumer
    {
        return new PhpFpmConsumer($destination);
    }

    public function close(): void
    {
    }

    public function createTemporaryQueue(): Queue
    {
        throw TemporaryQueueNotSupportedException::providerDoestNotSupportIt();
    }

    public function createSubscriptionConsumer(): SubscriptionConsumer
    {
        throw SubscriptionConsumerNotSupportedException::providerDoestNotSupportIt();
    }

    public function purgeQueue(Queue $queue): void
    {
        throw PurgeQueueNotSupportedException::providerDoestNotSupportIt();
    }
}