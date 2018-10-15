<?php
namespace Makasim\PhpFpm;

use hollodotme\FastCGI\Client;
use hollodotme\FastCGI\Requests\PostRequest;
use Interop\Queue\Destination;
use Interop\Queue\Exception\DeliveryDelayNotSupportedException;
use Interop\Queue\Exception\Exception;
use Interop\Queue\Exception\PriorityNotSupportedException;
use Interop\Queue\Exception\TimeToLiveNotSupportedException;
use Interop\Queue\Message;
use Interop\Queue\Producer;

class PhpFpmProducer implements Producer
{
    /**
     * @var Client
     */
    private $cgiClient;

    public function __construct(Client $cgiClient)
    {
        $this->cgiClient = $cgiClient;
    }

    /**
     * @param PhpFpmDestination $destination
     * @param PhpFpmMessage $message
     */
    public function send(Destination $destination, Message $message): void
    {
        $request = new PostRequest($destination->getName(), http_build_query(['message' => json_encode($message)]));

        try {
            $this->cgiClient->sendAsyncRequest($request);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function setDeliveryDelay(int $deliveryDelay = null): Producer
    {
        if (null !== $deliveryDelay) {
            throw DeliveryDelayNotSupportedException::providerDoestNotSupportIt();
        }

        return $this;
    }

    public function getDeliveryDelay(): ?int
    {
        return null;
    }

    public function setPriority(int $priority = null): Producer
    {
        if (null !== $priority) {
            throw PriorityNotSupportedException::providerDoestNotSupportIt();
        }

        return $this;
    }

    public function getPriority(): ?int
    {
        return null;
    }

    public function setTimeToLive(int $timeToLive = null): Producer
    {
        if (null !== $timeToLive) {
            throw TimeToLiveNotSupportedException::providerDoestNotSupportIt();
        }

        return $this;
    }

    public function getTimeToLive(): ?int
    {
        return null;
    }
}
