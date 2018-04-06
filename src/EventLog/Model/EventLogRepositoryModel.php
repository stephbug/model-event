<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\EventLog\Model;

use Illuminate\Support\Collection;

interface EventLogRepositoryModel
{
    public function createStream(string $uuid, string $stream, string $realStreamName, string $payload, int $version): void;

    public function eventsOfId(string $id): ?EventLogModel;

    public function eventsOfStream(string $stream): Collection;

    public function getLastVersionOfStream(string $streamName): ?int;
}