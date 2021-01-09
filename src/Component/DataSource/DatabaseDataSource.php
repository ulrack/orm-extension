<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Component\DataSource;

use Ulrack\Dbal\Sql\Common\ComparatorEnum;
use Ulrack\Dbal\Common\ConnectionInterface;
use Ulrack\Dbal\Common\QueryResultInterface;
use Ulrack\Dbal\Common\QueryFilterGroupInterface;
use Ulrack\Dbal\Sql\Component\Query\Data\DeleteQuery;
use Ulrack\Dbal\Sql\Component\Query\Data\InsertQuery;
use Ulrack\Dbal\Sql\Component\Query\Data\SelectQuery;
use Ulrack\Dbal\Sql\Component\Query\Data\UpdateQuery;
use Ulrack\Dbal\Sql\Component\Filter\ComparatorFilter;
use Ulrack\Dbal\Sql\Component\Filter\QueryFilterGroup;
use Ulrack\OrmExtension\Common\Applicator\ApplicatorInterface;
use Ulrack\OrmExtension\Common\DataSource\DataSourceInterface;
use GrizzIt\Search\Common\SearchCriteriaInterface;

class DatabaseDataSource implements DataSourceInterface
{
    /**
     * Contains the database connection.
     *
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * Contains the table on which the queries are performed.
     *
     * @var string
     */
    private $table;

    /**
     * Contains the field names of the table.
     *
     * @var string[]
     */
    private $fields;

    /**
     * Contains the query applicator.
     *
     * @var ApplicatorInterface
     */
    private $applicator;

    /**
     * Constructor.
     *
     * @param ConnectionInterface $connection
     * @param ApplicatorInterface $applicator
     * @param string $table
     * @param string ...$fields
     */
    public function __construct(
        ConnectionInterface $connection,
        ApplicatorInterface $applicator,
        string $table,
        string ...$fields
    ) {
        $this->connection = $connection;
        $this->applicator = $applicator;
        $this->table = $table;
        $this->fields = $fields;
    }

    /**
     * Searches through the data source and retrieves the result.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return QueryResultInterface
     */
    public function search(
        SearchCriteriaInterface $searchCriteria
    ): QueryResultInterface {
        $query = new SelectQuery($this->table);
        if (count($this->fields) > 0) {
            foreach ($this->fields as $field) {
                $query->addColumn($field);
            }
        }

        $this->applicator->apply($searchCriteria, $query);

        return $this->connection->query($query);
    }

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
    ): QueryResultInterface {
        $query = new UpdateQuery($this->table);
        foreach ($data as $key => $columnValue) {
            $query->addColumn($key, $columnValue);
        }

        $this->applicator->apply($searchCriteria, $query);

        return $this->connection->query($query);
    }

    /**
     * Delete data source entries based on search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return QueryResultInterface
     */
    public function deleteByCriteria(
        SearchCriteriaInterface $searchCriteria
    ): QueryResultInterface {
        $query = new DeleteQuery($this->table);
        $this->applicator->apply($searchCriteria, $query);

        return $this->connection->query($query);
    }

    /**
     * Retrieves the last inserted id.
     *
     * @return string
     */
    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Inserts an entry into a data source.
     *
     * @param array $data
     *
     * @return QueryResultInterface
     */
    public function insert(array $data): QueryResultInterface
    {
        $query = new InsertQuery($this->table);
        foreach ($data as $key => $value) {
            $query->addColumn($key, $value);
        }

        return $this->connection->query($query);
    }

    /**
     * Retrieves the results by a field name from the data source.
     *
     * @param string $field
     * @param mixed $value
     *
     * @return QueryResultInterface
     */
    public function getByField(string $field, $value): QueryResultInterface
    {
        $query = new SelectQuery($this->table);
        if (count($this->fields) > 0) {
            foreach ($this->fields as $field) {
                $query->addColumn($field);
            }
        }

        $query->addFilterGroup($this->getEqualFilter($field, $value));

        return $this->connection->query($query);
    }

    /**
     * Generate a filter based on a single key value pair.
     *
     * @param string $field
     * @param mixed $value
     *
     * @return QueryFilterGroupInterface
     */
    private function getEqualFilter(
        string $field,
        $value
    ): QueryFilterGroupInterface {
        $filter = new QueryFilterGroup();
        $filter->addFilter(
            new ComparatorFilter($field, $value, ComparatorEnum::EQ())
        );

        return $filter;
    }

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
    ): QueryResultInterface {
        $query = new UpdateQuery($this->table);
        foreach ($data as $key => $columnValue) {
            $query->addColumn($key, $columnValue);
        }

        $query->addFilterGroup($this->getEqualFilter($field, $value));

        return $this->connection->query($query);
    }

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
    ): QueryResultInterface {
        $query = new DeleteQuery($this->table);
        $query->addFilterGroup($this->getEqualFilter($field, $value));

        return $this->connection->query($query);
    }
}
