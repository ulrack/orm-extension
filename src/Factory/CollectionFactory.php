<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Factory;

use Generator;
use GrizzIt\Dbal\Common\QueryResultInterface;
use GrizzIt\Services\Common\Factory\ServiceFactoryInterface;
use Ulrack\OrmExtension\Common\Factory\ModelFactoryInterface;
use Ulrack\OrmExtension\Common\Collection\CollectionInterface;
use Ulrack\OrmExtension\Common\Factory\CollectionFactoryInterface;

class CollectionFactory implements CollectionFactoryInterface
{
    /**
     * Contains the model factory.
     *
     * @var ModelFactoryInterface
     */
    private $modelFactory;

    /**
     * Contains the service factory.
     *
     * @var ServiceFactoryInterface
     */
    private $serviceFactory;

    /**
     * Constructor.
     *
     * @param ModelFactoryInterface $modelFactory
     * @param ServiceFactoryInterface $serviceFactory
     */
    public function __construct(
        ModelFactoryInterface $modelFactory,
        ServiceFactoryInterface $serviceFactory
    ) {
        $this->modelFactory = $modelFactory;
        $this->serviceFactory = $serviceFactory;
    }

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
    ): CollectionInterface {
        return $this->serviceFactory->create(
            $collectionService,
            [
                'modelGenerator' => $this->getGenerator(
                    $modelService,
                    $queryResult
                ),
                'count' => $queryResult->count()
            ]
        );
    }

    /**
     * Creates a generator which can be passed to the collection.
     *
     * @param string $modelService
     * @param QueryResultInterface $queryResult
     *
     * @return Generator
     */
    private function getGenerator(
        string $modelService,
        QueryResultInterface $queryResult
    ): Generator {
        foreach ($queryResult as $item) {
            yield $this->modelFactory->create($modelService, $item);
        }
    }
}
