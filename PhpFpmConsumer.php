<?php
namespace Makasim\PhpFpm;

use Interop\Queue\Consumer;
use Interop\Queue\Message;
use Interop\Queue\Queue;

class PhpFpmConsumer implements Consumer
{
    /**
     * @var PhpFpmDestination
     */
    private $destination;

    public function __construct(PhpFpmDestination $destination)
    {
        $this->destination = $destination;
    }

    public function getQueue(): Queue
    {
        return $this->destination;
    }

    public function receive(int $timeout = 0): ?Message
    {
        return $this->receiveNoWait();
    }

    public function receiveNoWait(): ?Message
    {
        if ($_POST['message']) {
            return PhpFpmMessage::jsonUnserialize($_POST['message']);
        }

        return null;
    }

    public function acknowledge(Message $message): void
    {
    }

    public function reject(Message $message, bool $requeue = false): void
    {
    }
}
