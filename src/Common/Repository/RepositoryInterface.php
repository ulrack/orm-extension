<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Common\Repository;

use GrizzIt\Search\Common\SearchCriteriaInterface;
use Ulrack\OrmExtension\Common\Model\ModelInterface;
use Ulrack\OrmExtension\Common\Collection\CollectionInterface;

interface RepositoryInterface
{
    /**
     * Deletes the entity from the database.
     *
     * @param ModelInterface $model
     *
     * @return bool
     */
    public function delete(ModelInterface $model): bool;

    /**
     * Saves the model in the database.
     *
     * @param ModelInterface $model
     *
     * @return bool
     */
    public function save(ModelInterface $model): bool;

    /**
     * Retrieve all entries from the database.
     *
     * @return CollectionInterface
     */
    public function getAll(): CollectionInterface;

    /**
     * Search through the database and retrieve the collection.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return CollectionInterface
     */
    public function search(
        SearchCriteriaInterface $searchCriteria
    ): CollectionInterface;
}
