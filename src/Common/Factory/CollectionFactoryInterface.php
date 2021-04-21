<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Common\Factory;

use GrizzIt\Dbal\Common\QueryResultInterface;
use Ulrack\OrmExtension\Common\Collection\CollectionInterface;

interface CollectionFactoryInterface
{
    /**
     * Creates a collection based on a model service.
     *
     * @param string $collectionService
     * @param string $modelService
     * @param QueryResultInterface $queryResult
     *
     * @return CollectionInterface
     */
    public function create(
        string $collectionService,
        string $modelService,
        QueryResultInterface $queryResult
    ): CollectionInterface;
}
