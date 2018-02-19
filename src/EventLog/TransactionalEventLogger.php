<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\EventLog;

interface TransactionalEventLogger extends EventLogger
{
    public function beginTransaction(): void;

    public function rollBack(): void;

    public function commit(): void;

    public function inTransaction(): bool;
}