<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Tests\Mock;

use Ulrack\OrmExtension\Common\Model\ModelInterface;
use Ulrack\OrmExtension\Component\Repository\AbstractRepository;

class MockRepository extends AbstractRepository
{
    /**
     * Deletes the entity from the database.
     *
     * @param ModelInterface $model
     *
     * @return bool
     */
    public function delete(ModelInterface $model): bool
    {
        return true;
    }

    /**
     * Saves the model in the database.
     *
     * @param ModelInterface $model
     *
     * @return bool
     */
    public function save(ModelInterface $model): bool
    {
        return true;
    }
}
