<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\EventLog\Stream;

class StreamName
{
    /**
     * @var string
     */
    private $name;

    /**
     * StreamName constructor.
     *
     * @param string $streamName
     */
    public function __construct(string $streamName)
    {
        $this->name = $streamName;
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}