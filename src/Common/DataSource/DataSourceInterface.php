<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Common\DataSource;

use Ulrack\Dbal\Common\QueryResultInterface;
use GrizzIt\Search\Common\SearchCriteriaInterface;

interface DataSourceInterface
{
    /**
     * Searches through the data source and retrieves the result.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return QueryResultInterface
     */
    public function search(
        SearchCriteriaInterface $searchCriteria
    ): QueryResultInterface;

    /**
     * Updates a data source based on data and search criteria.
     *
     * @param array $data
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return QueryResultInterface
     */
    public function updateByCriteria(
        array $data,
        SearchCriteriaInterface $searchCriteria
    ): QueryResultInterface;

    /**
     * Delete data source entries based on search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return QueryResultInterface
     */
    public function deleteByCriteria(
        SearchCriteriaInterface $searchCriteria
    ): QueryResultInterface;

    /**
     * Inserts an entry into a data source.
     *
     * @param array $data
     *
     * @return QueryResultInterface
     */
    public function insert(array $data): QueryResultInterface;

    /**
     * Retrieves the last inserted id.
     *
     * @return string
     */
    public function lastInsertId(): string;

    /**
     * Retrieves the results by a field name from the data source.
     *
     * @param string $field
     * @param mixed $value
     *
     * @return QueryResultInterface
     */
    public function getByField(string $field, $value): QueryResultInterface;

    /**
     * Updates a data source based on a field.
     *
     * @param string $field
     * @param mixed $value
     * @param array $data
     *
     * @return QueryResultInterface
     */
    public function updateByField(
        string $field,
        $value,
        array $data
    ): QueryResultInterface;

    /**
     * Delete a data source entry based on a field.
     *
     * @param string $field
     * @param mixed $value
     *
     * @return QueryResultInterface
     */
    public function deleteByField(
        string $field,
        $value
    ): QueryResultInterface;
}
