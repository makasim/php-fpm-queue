<?php
namespace Makasim\PhpFpm;

use Enqueue\Dsn\Dsn;
use hollodotme\FastCGI\Client;
use hollodotme\FastCGI\SocketConnections\NetworkSocket;
use hollodotme\FastCGI\SocketConnections\UnixDomainSocket;
use Interop\Queue\ConnectionFactory;
use Interop\Queue\Context;

class PhpFpmConnectionFactory implements ConnectionFactory
{
    /**
     * @var string
     */
    private $dsn;

    public function __construct(string $dsn)
    {
        $this->dsn = $dsn;
    }

    public function createContext(): Context
    {
        $dsn = Dsn::parseFirst($this->dsn);
        if ('unix' == $dsn->getSchemeProtocol()) {
            $socket = new UnixDomainSocket($dsn->getPath());
        } else if ('tcp' == $dsn->getSchemeProtocol()) {
            $socket = new NetworkSocket($dsn->getHost(), $dsn->getPort());
        } else {
            throw new \LogicException('Protocol is not supported');
        }

        return new PhpFpmContext(new Client($socket));
    }
}
