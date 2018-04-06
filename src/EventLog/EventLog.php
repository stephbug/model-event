<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\EventLog;

use Illuminate\Database\Connection;
use StephBug\ModelEvent\EventLog\Commander\CreateStream;
use StephBug\ModelEvent\EventLog\Model\EloquentEventLog;
use StephBug\ModelEvent\EventLog\Stream\Stream;
use StephBug\ModelEvent\ModelChanged;
use StephBug\ServiceBus\Bus\CommandBus;
use StephBug\ServiceBus\Bus\EventBus;

class EventLog implements TransactionalEventLogger
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var EloquentEventLog
     */
    private $model;

    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var bool
     */
    private $inTransaction = false;

    public function __construct(Connection $connection,
                                EloquentEventLog $model,
                                EventBus $eventBus,
                                CommandBus $commandBus)
    {
        $this->connection = $connection;
        $this->model = $model;
        $this->eventBus = $eventBus;
        $this->commandBus = $commandBus;
    }

    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();

        $this->inTransaction = true;
    }

    public function rollBack(): void
    {
        $this->connection->rollBack();
    }

    public function commit(): void
    {
        $this->connection->commit();
    }

    public function inTransaction(): bool
    {
        return $this->inTransaction;
    }

    public function create(Stream $stream): void
    {
        $streamString = $stream->streamName()->toString();

        /** @var ModelChanged $event */
        foreach ($stream->streamEvents() as $event) {

            $this->eventBus->dispatch($event);

            $payload = [
                'id' => $event->uuid(),
                'stream' => $streamString,
                'stream_name' => $event->messageName(),
                'payload' => json_encode($event->toArray()),
                'version' => $event->version()
            ];

            $this->commandBus->dispatch(new CreateStream($payload));
        }
    }

    public function model(): EloquentEventLog
    {
        return $this->model;
    }
}