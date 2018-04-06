<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\EventLog\Commander;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Prooph\ServiceBus\Async\AsyncMessage;

class CreateStream extends Command implements PayloadConstructable, AsyncMessage
{
    use PayloadTrait;
}