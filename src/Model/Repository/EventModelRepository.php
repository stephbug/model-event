<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\Model\Repository;

use Prooph\Common\Messaging\Message;
use StephBug\ModelEvent\EventLog\EventLog;
use StephBug\ModelEvent\EventLog\Stream\Stream;
use StephBug\ModelEvent\EventLog\Stream\StreamName;
use StephBug\ModelEvent\ModelRoot;

abstract class EventModelRepository implements Repository
{
    /**
     * @var EventLog
     */
    protected $eventLog;

    /**
     * @var ModelRead|RepositoryRead
     */
    protected $readModel;

    /**
     * @var StreamName
     */
    protected $streamName;

    public function __construct(EventLog $eventLog, ModelRead $readModel, StreamName $streamName)
    {
        $this->eventLog = $eventLog;
        $this->readModel = $readModel;
        $this->streamName = $streamName;
    }

    public function saveAggregateRoot(ModelRoot $root): void
    {
        $this->assertModelType($root);

        $domainEvents = $root->popRecordedEvents();
        $rootId = $this->getAggregateId($root);
        $streamName = $this->determineStreamName($rootId);

        $firstEvent = reset($domainEvents);
        if (false === $firstEvent) {
            return;
        }

        $enrichedEvents = [];
        foreach ($domainEvents as $event) {
            $enrichedEvents [] = $this->enrichEventMetadata($event, $rootId);
        }

        $stream = new Stream($streamName, $enrichedEvents);

        $this->eventLog->create($stream);
    }

    protected function determineStreamName(string $rootId): StreamName
    {
        return new StreamName($this->streamName->toString() . '-' . $rootId);
    }

    protected function getAggregateId(ModelRoot $root): string
    {
        $class = new \ReflectionMethod($root, 'aggregateId');
        $class->setAccessible(true);

        return $class->invoke($root);
    }

    protected function enrichEventMetadata(Message $domainEvent, string $aggregateId): Message
    {
        $domainEvent = $domainEvent->withAddedMetadata('_aggregate_id', $aggregateId);
        $domainEvent = $domainEvent->withAddedMetadata('_aggregate_type', get_class($this->readModel));

        return $domainEvent;
    }

    abstract public function assertModelType(ModelRoot $model): void;
}