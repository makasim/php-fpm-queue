# PHP-FPM Async Queue

Use php-fpm as a simple built-in async queue. Based on interoperable queue interfaces [Queue Interop](https://github.com/queue-interop/queue-interop).  

## Usage

```bash
composer makasim/php-fpm-queue:0.1.x-dev queue-interop/queue-interop:0.7.x-dev enqueue/dsn:0.9.x-dev
```

A sender script:

```php
<?php
# sender.php

use Makasim\PhpFpm\PhpFpmConnectionFactory;

require_once __DIR__.'/vendor/autoload.php';

$context = (new PhpFpmConnectionFactory('tcp://localhost:9000'))->createContext();

$queue = $context->createQueue('/app/worker.php');
$message = $context->createMessage('aBody');

$context->createProducer()->send($queue, $message);
```

A worker script:


```php
<?php
# worker.php

use Makasim\PhpFpm\PhpFpmConnectionFactory;

require_once __DIR__.'/vendor/autoload.php';

$context = (new PhpFpmConnectionFactory('tcp://localhost:9000'))->createContext();

$queue = $context->createQueue(__FILE__);

$consumer = $context->createConsumer($queue);

if ($message = $consumer->receiveNoWait()) {
    // process message

    $consumer->acknowledge($message);
}
```

Start PHP-FPM:

```bash
docker run -v `pwd`:/app -p 9000:9000 php:7.2-fpm
```

Send a message:

```bash
php sender.php
```

## Credits

Inspired by Benjamin post [Using php-fpm as a simple built-in async queue](https://tideways.com/profiler/blog/using-php-fpm-as-a-simple-built-in-async-queue)
            