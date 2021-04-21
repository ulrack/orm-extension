<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Component\Collection;

use Iterator;
use Generator;
use Ulrack\OrmExtension\Common\Model\ModelInterface;
use Ulrack\OrmExtension\Common\Collection\CollectionInterface;

class Collection implements Iterator, CollectionInterface
{
    /**
     * Contains the model generator.
     *
     * @var Generator
     */
    private $modelGenerator;

    /**
     * Contains the amount of items in the collection.
     *
     * @var int
     */
    private $count;

    /**
     * Contains the counter for the collection.
     *
     * @var int
     */
    private $iteratorStep = 0;

    /**
     * Contains the previously constructed models.
     *
     * @var array
     */
    private $models = [];

    /**
     * Constructor.
     *
     * @param Generator $modelGenerator
     */
    public function __construct(Generator $modelGenerator, int $count)
    {
        $this->modelGenerator = $modelGenerator;
        $this->count = $count;
    }

    /**
     * Retrieves all items from the collection.
     *
     * @return ModelInterface[]
     */
    public function getAllItems(): array
    {
        if (count($this->models) < $this->count) {
            $this->valid();
            $this->modelGenerator->next();
            while ($this->modelGenerator->valid()) {
                $this->models[] = $this->modelGenerator->current();
                $this->modelGenerator->next();
            }
        }

        return $this->models;
    }

    /**
     * Returns the amount of items in the result set.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->count;
    }

    /**
     * Retrieves the model on the current iterator step.
     *
     * @return ModelInterface
     */
    public function current(): ModelInterface
    {
        return $this->models[$this->iteratorStep];
    }

    /**
     * Retrieves the key of the current iterator step.
     *
     * @return int
     */
    public function key(): int
    {
        return $this->iteratorStep;
    }

    /**
     * Goes to the next step in the iterator.
     *
     * @return void
     */
    public function next(): void
    {
        $this->iteratorStep++;
        if (!$this->valid()) {
            $this->modelGenerator->next();
            $this->models[] = $this->modelGenerator->current();
        }
    }

    /**
     * Rewinds the iterator.
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->iteratorStep = 0;
    }

    /**
     * Verifies whether the current iterator step is valid.
     *
     * @return bool
     */
    public function valid(): bool
    {
        if (
            $this->iteratorStep === 0 &&
            $this->modelGenerator->valid() &&
            $this->modelGenerator->key() === 0
        ) {
            $this->models[] = $this->modelGenerator->current();
        }

        return isset($this->models[$this->iteratorStep]);
    }
}
