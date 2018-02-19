<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\Model\Repository;

use StephBug\ModelEvent\ModelRoot;
use StephBug\ServiceBus\Bus\EventBus;

class ModelRepository implements Repository
{
    /**
     * @var EventBus
     */
    private $eventBus;

    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function saveAggregateRoot(ModelRoot $modelRoot): void
    {
        $events = $modelRoot->popRecordedEvents();

        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}