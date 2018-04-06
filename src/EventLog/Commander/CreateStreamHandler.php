<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\EventLog\Commander;

use StephBug\ModelEvent\EventLog\EventLogger;
use StephBug\ModelEvent\EventLog\Model\EloquentEventLog;

class CreateStreamHandler
{
    /**
     * @var EventLogger
     */
    private $eventLog;

    public function __construct(EventLogger $eventLog)
    {
        $this->eventLog = $eventLog;
    }

    public function __invoke(CreateStream $command): void
    {
        /** @var EloquentEventLog $model */
        $model = $this->eventLog->model();

        $model->createStream(
            $command->payload()['id'],
            $command->payload()['stream'],
            $command->payload()['stream_name'],
            $command->payload()['payload'],
            $command->payload()['version']
        );
    }
}