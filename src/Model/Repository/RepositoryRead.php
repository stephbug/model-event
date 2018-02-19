<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\Model\Repository;

use Illuminate\Database\Eloquent\Model;

interface RepositoryRead extends ModelRead
{
    public function createModel(): Model;
}