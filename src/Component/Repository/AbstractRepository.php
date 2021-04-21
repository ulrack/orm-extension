<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Component\Repository;

use GrizzIt\Dbal\Common\QueryResultInterface;
use GrizzIt\Search\Common\SearchCriteriaInterface;
use GrizzIt\Search\Component\Criteria\SearchCriteria;
use Ulrack\OrmExtension\Common\Collection\CollectionInterface;
use Ulrack\OrmExtension\Common\DataSource\DataSourceInterface;
use Ulrack\OrmExtension\Common\Repository\RepositoryInterface;
use Ulrack\OrmExtension\Common\Factory\CollectionFactoryInterface;

abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * Contains the model service name.
     *
     * @var string
     */
    private $modelService;

    /**
     * Contains the collection service name.
     *
     * @var string
     */
    private $collectionService;

    /**
     * Contains the collection factory.
     *
     * @var CollectionFactoryInterface
     */
    private $collectionFactory;

    /**
     * Contains the data source.
     *
     * @var DataSourceInterface
     */
    private $dataSource;

    /**
     * Constructor.
     *
     * @param string $modelService
     * @param string $collectionService
     * @param CollectionFactoryInterface $collectionFactory
     * @param DataSourceInterface $dataSource
     */
    public function __construct(
        string $modelService,
        string $collectionService,
        CollectionFactoryInterface $collectionFactory,
        DataSourceInterface $dataSource
    ) {
        $this->modelService = $modelService;
        $this->collectionService = $collectionService;
        $this->collectionFactory = $collectionFactory;
        $this->dataSource = $dataSource;
    }

    /**
     * Retrieves the collection factory.
     *
     * @return CollectionFactoryInterface
     */
    public function getCollectionFactory(): CollectionFactoryInterface
    {
        return $this->collectionFactory;
    }

    /**
     * Retrieves the data source.
     *
     * @return DataSourceInterface
     */
    public function getDataSource(): DataSourceInterface
    {
        return $this->dataSource;
    }

    /**
     * Retrieves the collection class name.
     *
     * @return string
     */
    public function getCollectionService(): string
    {
        return $this->collectionService;
    }

    /**
     * Retrieves the model class name.
     *
     * @return string
     */
    public function getModelService(): string
    {
        return $this->modelService;
    }

    /**
     * Retrieve all entries from the database.
     *
     * @return CollectionInterface
     */
    public function getAll(): CollectionInterface
    {
        return $this->search(new SearchCriteria());
    }

    /**
     * Search through the database and retrieve the collection.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return CollectionInterface
     */
    public function search(
        SearchCriteriaInterface $searchCriteria
    ): CollectionInterface {
        return $this->createCollectionFromResult(
            $this->dataSource->search($searchCriteria)
        );
    }

    /**
     * Creates a collection based on a query result.
     *
     * @param QueryResultInterface $result
     *
     * @return CollectionInterface
     */
    public function createCollectionFromResult(
        QueryResultInterface $result
    ): CollectionInterface {
        return $this->collectionFactory->create(
            $this->collectionService,
            $this->modelService,
            $result
        );
    }
}
