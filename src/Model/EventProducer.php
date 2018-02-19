<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\Model;

use StephBug\ModelEvent\ModelChanged;

trait EventProducer
{
    /**
     * @var array
     */
    protected $recordedEvents = [];

    public function popRecordedEvents(): array
    {
        $pendingEvents = $this->recordedEvents;

        $this->recordedEvents = [];

        return $pendingEvents;
    }

    protected function recordThat(ModelChanged $event): void
    {
        $this['version'] = $this['version'] + 1;

        $this->recordedEvents[] = $event->withVersion($this['version']);

        $this->apply($event);
    }

    abstract protected function aggregateId(): string;

    abstract public function apply(ModelChanged $event): void;
}