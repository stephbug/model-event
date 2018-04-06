<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\EventLog\Model;

use Ramsey\Uuid\UuidInterface;
use StephBug\ModelEvent\EventLog\Stream\StreamName;

interface EventLogModel
{
    public function getId(): UuidInterface;

    public function getRealStreamName(): StreamName;

    public function getStreamName(): StreamName;

    public function getPayload(): string;

    public function getVersion(): int;
}