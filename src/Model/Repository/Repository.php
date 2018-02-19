<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\Model\Repository;

use StephBug\ModelEvent\ModelRoot;

interface Repository
{
    public function saveAggregateRoot(ModelRoot $modelRoot): void;
}