<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Tests\Component\Repository;

use PHPUnit\Framework\TestCase;
use Ulrack\Dbal\Common\QueryResultInterface;
use GrizzIt\Search\Common\SearchCriteriaInterface;
use Ulrack\OrmExtension\Tests\Mock\MockRepository;
use Ulrack\OrmExtension\Common\Collection\CollectionInterface;
use Ulrack\OrmExtension\Common\DataSource\DataSourceInterface;
use Ulrack\OrmExtension\Common\Factory\CollectionFactoryInterface;

/**
 * @coversDefaultClass \Ulrack\OrmExtension\Component\Repository\AbstractRepository
 */
class RepositoryTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getCollectionFactory
     * @covers ::getDataSource
     * @covers ::getCollectionService
     * @covers ::getModelService
     * @covers ::getAll
     * @covers ::search
     * @covers ::createCollectionFromResult
     *
     * @return void
     */
    public function testComponent(): void
    {
        $collectionFactory = $this->createMock(
            CollectionFactoryInterface::class
        );

        $dataSource = $this->createMock(DataSourceInterface::class);
        $collection = $this->createMock(CollectionInterface::class);
        $result = $this->createMock(QueryResultInterface::class);
        $subject = new MockRepository(
            'services.model',
            'services.collection',
            $collectionFactory,
            $dataSource
        );

        $this->assertSame(
            $collectionFactory,
            $subject->getCollectionFactory()
        );

        $this->assertSame(
            $dataSource,
            $subject->getDataSource()
        );

        $this->assertEquals(
            'services.collection',
            $subject->getCollectionService()
        );

        $this->assertEquals(
            'services.model',
            $subject->getModelService()
        );

        $dataSource->expects(static::once())
            ->method('search')
            ->with($this->isInstanceOf(SearchCriteriaInterface::class))
            ->willReturn($result);

        $collectionFactory->expects(static::once())
            ->method('create')
            ->with('services.collection', 'services.model', $result)
            ->willReturn($collection);

        $this->assertEquals($collection, $subject->getAll());
    }
}
