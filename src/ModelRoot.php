<?php

declare(strict_types=1);

namespace StephBug\ModelEvent;

use Illuminate\Database\Eloquent\Model;
use StephBug\ModelEvent\Model\EventProducer;
use StephBug\ModelEvent\Model\Timestampable;

abstract class ModelRoot extends Model
{
    use EventProducer, Timestampable;

    public function apply(ModelChanged $event): void
    {
        $handler = $this->determineEventHandlerMethodFor($event);

        if (!method_exists($this, $handler)) {
            throw new \RuntimeException(sprintf(
                'Missing event handler method %s for aggregate root %s',
                $handler,
                get_class($this)
            ));
        }

        $this->{$handler}($event);
    }

    protected function determineEventHandlerMethodFor(ModelChanged $event): string
    {
        return 'when' . implode(array_slice(explode('\\', get_class($event)), -1));
    }
}