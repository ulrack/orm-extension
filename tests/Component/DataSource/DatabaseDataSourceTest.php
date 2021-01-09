<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Tests\Component\DataSource;

use PHPUnit\Framework\TestCase;
use Ulrack\Dbal\Common\ConnectionInterface;
use Ulrack\Dbal\Common\QueryResultInterface;
use GrizzIt\Search\Common\SearchCriteriaInterface;
use Ulrack\Dbal\Sql\Component\Query\Data\DeleteQuery;
use Ulrack\Dbal\Sql\Component\Query\Data\InsertQuery;
use Ulrack\Dbal\Sql\Component\Query\Data\SelectQuery;
use Ulrack\Dbal\Sql\Component\Query\Data\UpdateQuery;
use Ulrack\OrmExtension\Common\Applicator\ApplicatorInterface;
use Ulrack\OrmExtension\Component\DataSource\DatabaseDataSource;

/**
 * @coversDefaultClass \Ulrack\OrmExtension\Component\DataSource\DatabaseDataSource
 */
class DatabaseDataSourceTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::search
     *
     * @return void
     */
    public function testSearch(): void
    {
        $connection = $this->createMock(ConnectionInterface::class);
        $applicator = $this->createMock(ApplicatorInterface::class);
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $result = $this->createMock(QueryResultInterface::class);
        $subject = new DatabaseDataSource(
            $connection,
            $applicator,
            'foo_table',
            'bar_field',
            'baz_field'
        );

        $applicator->expects(static::once())
            ->method('apply')
            ->with($searchCriteria, $this->isInstanceOf(SelectQuery::class));

        $connection->expects(static::once())
            ->method('query')
            ->with($this->isInstanceOf(SelectQuery::class))
            ->willReturn($result);

        $this->assertSame($result, $subject->search($searchCriteria));
    }

    /**
     * @covers ::__construct
     * @covers ::updateByCriteria
     *
     * @return void
     */
    public function testUpdateByCriteria(): void
    {
        $connection = $this->createMock(ConnectionInterface::class);
        $applicator = $this->createMock(ApplicatorInterface::class);
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $result = $this->createMock(QueryResultInterface::class);
        $subject = new DatabaseDataSource(
            $connection,
            $applicator,
            'foo_table',
            'bar_field',
            'baz_field'
        );

        $applicator->expects(static::once())
            ->method('apply')
            ->with($searchCriteria, $this->isInstanceOf(UpdateQuery::class));

        $connection->expects(static::once())
            ->method('query')
            ->with($this->isInstanceOf(UpdateQuery::class))
            ->willReturn($result);

        $this->assertSame(
            $result,
            $subject->updateByCriteria(['foo' => 'bar'], $searchCriteria)
        );
    }

    /**
     * @covers ::__construct
     * @covers ::deleteByCriteria
     *
     * @return void
     */
    public function testDeleteByCriteria(): void
    {
        $connection = $this->createMock(ConnectionInterface::class);
        $applicator = $this->createMock(ApplicatorInterface::class);
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $result = $this->createMock(QueryResultInterface::class);
        $subject = new DatabaseDataSource(
            $connection,
            $applicator,
            'foo_table',
            'bar_field',
            'baz_field'
        );

        $applicator->expects(static::once())
            ->method('apply')
            ->with($searchCriteria, $this->isInstanceOf(DeleteQuery::class));

        $connection->expects(static::once())
            ->method('query')
            ->with($this->isInstanceOf(DeleteQuery::class))
            ->willReturn($result);

        $this->assertSame(
            $result,
            $subject->deleteByCriteria($searchCriteria)
        );
    }

    /**
     * @covers ::__construct
     * @covers ::lastInsertId
     *
     * @return void
     */
    public function testLastInsertId(): void
    {
        $connection = $this->createMock(ConnectionInterface::class);
        $subject = new DatabaseDataSource(
            $connection,
            $this->createMock(ApplicatorInterface::class),
            'foo_table',
            'bar_field',
            'baz_field'
        );

        $connection->expects(static::once())
            ->method('lastInsertId')
            ->willReturn('1');

        $this->assertEquals('1', $subject->lastInsertId());
    }

    /**
     * @covers ::__construct
     * @covers ::insert
     *
     * @return void
     */
    public function testInsert(): void
    {
        $connection = $this->createMock(ConnectionInterface::class);
        $result = $this->createMock(QueryResultInterface::class);
        $subject = new DatabaseDataSource(
            $connection,
            $this->createMock(ApplicatorInterface::class),
            'foo_table',
            'bar_field',
            'baz_field'
        );

        $connection->expects(static::once())
            ->method('query')
            ->with($this->isInstanceOf(InsertQuery::class))
            ->willReturn($result);

        $this->assertSame(
            $result,
            $subject->insert(['foo' => 'bar'])
        );
    }

    /**
     * @covers ::__construct
     * @covers ::getByField
     * @covers ::getEqualFilter
     *
     * @return void
     */
    public function testGetByField(): void
    {
        $connection = $this->createMock(ConnectionInterface::class);
        $result = $this->createMock(QueryResultInterface::class);
        $subject = new DatabaseDataSource(
            $connection,
            $this->createMock(ApplicatorInterface::class),
            'foo_table',
            'bar_field',
            'baz_field'
        );

        $connection->expects(static::once())
            ->method('query')
            ->with($this->isInstanceOf(SelectQuery::class))
            ->willReturn($result);

        $this->assertSame($result, $subject->getByField('foo', 'bar'));
    }

    /**
     * @covers ::__construct
     * @covers ::updateByField
     * @covers ::getEqualFilter
     *
     * @return void
     */
    public function testUpdateByField(): void
    {
        $connection = $this->createMock(ConnectionInterface::class);
        $result = $this->createMock(QueryResultInterface::class);
        $subject = new DatabaseDataSource(
            $connection,
            $this->createMock(ApplicatorInterface::class),
            'foo_table',
            'bar_field',
            'baz_field'
        );

        $connection->expects(static::once())
            ->method('query')
            ->with($this->isInstanceOf(UpdateQuery::class))
            ->willReturn($result);

        $this->assertSame(
            $result,
            $subject->updateByField('foo', 'bar', ['baz' => 'qux'])
        );
    }

    /**
     * @covers ::__construct
     * @covers ::deleteByField
     * @covers ::getEqualFilter
     *
     * @return void
     */
    public function testDeleteByField(): void
    {
        $connection = $this->createMock(ConnectionInterface::class);
        $result = $this->createMock(QueryResultInterface::class);
        $subject = new DatabaseDataSource(
            $connection,
            $this->createMock(ApplicatorInterface::class),
            'foo_table',
            'bar_field',
            'baz_field'
        );

        $connection->expects(static::once())
            ->method('query')
            ->with($this->isInstanceOf(DeleteQuery::class))
            ->willReturn($result);

        $this->assertSame($result, $subject->deleteByField('foo', 'bar'));
    }
}
