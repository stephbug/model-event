<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\EventLog\Stream;

class Stream
{
    /**
     * @var StreamName
     */
    private $streamName;

    /**
     * @var array
     */
    private $streamEvents;

    /**
     * Stream constructor.
     *
     * @param StreamName $streamName
     * @param array $streamEvents
     */
    public function __construct(StreamName $streamName, array $streamEvents)
    {
        $this->streamName = $streamName;
        $this->streamEvents = $streamEvents;
    }

    public function streamName(): StreamName
    {
        return $this->streamName;
    }

    public function streamEvents(): array
    {
        return $this->streamEvents;
    }
}