<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Tests\Mock;

use Traversable;
use ArrayIterator;
use IteratorAggregate;
use Ulrack\Dbal\Common\QueryResultInterface;

class MockQueryResult implements IteratorAggregate, QueryResultInterface
{
    /**
     * Returns whether the query was a success or not.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return true;
    }

    /**
     * Retrieves all errors related to the query.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return [];
    }

    /**
     * Returns the status code of the query.
     *
     * @return string
     */
    public function getStatusCode(): string
    {
        return '1';
    }

    /**
     * Retrieves all rows from the result.
     *
     * @return array
     */
    public function fetchAll(): array
    {
        return [];
    }

    /**
     * Retrieve the iterator.
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator();
    }

    /**
     * Retrieves the amount of entries.
     *
     * @return int
     */
    public function count(): int
    {
        return 0;
    }
}
