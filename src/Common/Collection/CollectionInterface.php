<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Common\Collection;

use Countable;
use Traversable;
use Ulrack\OrmExtension\Common\Model\ModelInterface;

interface CollectionInterface extends Traversable, Countable
{
    /**
     * Retrieves all items from the collection.
     *
     * @return ModelInterface[]
     */
    public function getAllItems(): array;
}
