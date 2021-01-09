<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Tests\Component\Applicator;

use PHPUnit\Framework\TestCase;
use GrizzIt\Search\Common\DirectionEnum;
use GrizzIt\Search\Common\ComparatorEnum;
use GrizzIt\Search\Common\PagerInterface;
use GrizzIt\Search\Common\FilterInterface;
use GrizzIt\Search\Common\SorterInterface;
use GrizzIt\Search\Common\FilterGroupInterface;
use GrizzIt\Search\Common\SearchCriteriaInterface;
use Ulrack\Dbal\Sql\Component\Query\Data\SelectQuery;
use Ulrack\OrmExtension\Component\Applicator\Applicator;

/**
 * @coversDefaultClass \Ulrack\OrmExtension\Component\Applicator\Applicator
 */
class ApplicatorTest extends TestCase
{
    /**
     * @covers ::apply
     * @covers ::applyFilterGroup
     *
     * @return void
     */
    public function testApply(): void
    {
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $filterGroup = $this->createMock(FilterGroupInterface::class);
        $searchCriteria->expects(static::once())
            ->method('getFilterGroups')
            ->willReturn([$filterGroup]);

        $nestedFilterGroup = $this->createMock(FilterGroupInterface::class);
        $filterGroup->expects(static::once())
            ->method('getFilters')
            ->willReturn([$nestedFilterGroup]);

        $filter = $this->createMock(FilterInterface::class);
        $nestedFilterGroup->expects(static::once())
            ->method('getFilters')
            ->willReturn([$filter]);

        $filter->expects(static::once())
            ->method('getField')
            ->willReturn('foo');

        $filter->expects(static::once())
            ->method('getValue')
            ->willReturn('bar');

        $filter->expects(static::once())
            ->method('getComparator')
            ->willReturn(ComparatorEnum::EQ());

        $pager = $this->createMock(PagerInterface::class);
        $searchCriteria->expects(static::once())
            ->method('getPagers')
            ->willReturn([$pager]);

        $pager->expects(static::once())
            ->method('getAmount')
            ->willReturn(100);

        $pager->expects(static::once())
            ->method('getPage')
            ->willReturn(3);

        $sorter = $this->createMock(SorterInterface::class);
        $searchCriteria->expects(static::once())
            ->method('getSorters')
            ->willReturn([$sorter]);

        $sorter->expects(static::once())
            ->method('getField')
            ->willReturn('baz');

        $sorter->expects(static::once())
            ->method('getDirection')
            ->willReturn(DirectionEnum::ASC());

        $query = $this->createMock(SelectQuery::class);
        $subject = new Applicator();
        $subject->apply($searchCriteria, $query);
    }
}
