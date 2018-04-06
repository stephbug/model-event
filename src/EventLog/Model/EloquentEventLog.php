<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\EventLog\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use StephBug\ModelEvent\EventLog\Stream\StreamName;

class EloquentEventLog extends Model implements EventLogModel, EventLogRepositoryModel
{
    /**
     * @var string
     */
    protected $table = 'event_log';

    /**
     * @var array
     */
    protected $fillable = ['id', 'stream', 'real_stream_name', 'payload', 'version'];

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var bool
     */
    public $timestamps = false;

    public function createStream(string $uuid, string $stream, string $realStreamName, string $payload, int $version): void
    {
        $self = new self([
            'id' => $uuid,
            'stream' => $stream,
            'real_stream_name' => $realStreamName,
            'payload' => $payload,
            'version' => $version
        ]);

        $self->save();
    }

    public function eventsOfId(string $id): ?EventLogModel
    {
        return $this->newQuery()->find($id);
    }

    public function eventsOfStream(string $stream): Collection
    {
        return $this->newQuery()->where('stream', $stream)->get();
    }

    public function getLastVersionOfStream(string $streamName): ?int
    {
        $version = $this->newQuery()->where('stream', $streamName)->max('version');

        return $version ?? null;
    }

    public function getId(): UuidInterface
    {
        return Uuid::fromString($this->getKey());
    }

    public function getRealStreamName(): StreamName
    {
        return new StreamName($this['real_stream_name']);
    }

    public function getStreamName(): StreamName
    {
        return new StreamName($this['stream']);
    }

    public function getPayload(): string
    {
        return $this['payload'];
    }

    public function getVersion(): int
    {
        return $this['version'];
    }
}