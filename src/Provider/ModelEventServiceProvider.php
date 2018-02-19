<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\Provider;

use Illuminate\Support\ServiceProvider;

class ModelEventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database');
    }
}