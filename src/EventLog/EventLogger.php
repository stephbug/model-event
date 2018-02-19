<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\EventLog;

use StephBug\ModelEvent\EventLog\Stream\Stream;

interface EventLogger
{
    public function create(Stream $stream): void;
}