<?php

namespace StephBug\ModelEvent\Model;

trait Timestampable
{
    public function createdAt(): \DateTimeImmutable
    {
        if (is_string($this->{static::CREATED_AT})) {
            return new \DateTimeImmutable($this->{static::CREATED_AT});
        }

        return new \DateTimeImmutable($this->{static::CREATED_AT}->toDateTimeString());
    }

    public function updatedAt(): ?\DateTimeImmutable
    {
        if (null === $this->{static::UPDATED_AT}) {
            return null;
        }

        if (is_string($this->{static::UPDATED_AT})) {
            return new \DateTimeImmutable($this->{static::UPDATED_AT});
        }

        return new \DateTimeImmutable($this->{static::UPDATED_AT}->toDateTimeString());
    }
}