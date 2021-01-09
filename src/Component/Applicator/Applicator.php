<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Component\Applicator;

use Ulrack\Dbal\Common\QueryInterface;
use GrizzIt\Search\Common\DirectionEnum;
use GrizzIt\Search\Common\ComparatorEnum;
use GrizzIt\Search\Common\FilterInterface;
use Ulrack\Dbal\Common\Enum\SortDirectionEnum;
use Ulrack\Dbal\Common\PageableQueryInterface;
use Ulrack\Dbal\Common\SortableQueryInterface;
use GrizzIt\Search\Common\FilterGroupInterface;
use Ulrack\Dbal\Common\FilterableQueryInterface;
use GrizzIt\Search\Common\SearchCriteriaInterface;
use Ulrack\Dbal\Sql\Component\Filter\ComparatorFilter;
use Ulrack\Dbal\Sql\Component\Filter\QueryFilterGroup;
use Ulrack\OrmExtension\Common\Applicator\ApplicatorInterface;
use Ulrack\Dbal\Sql\Common\ComparatorEnum as SqlComparatorEnum;

class Applicator implements ApplicatorInterface
{
    /**
     * Applies a search criteria to a query.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param QueryInterface $query
     *
     * @return void
     */
    public function apply(
        SearchCriteriaInterface $searchCriteria,
        QueryInterface $query
    ): void {
        if ($query instanceof FilterableQueryInterface) {
            foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
                $this->applyFilterGroup($query, $filterGroup);
            }
        }

        if ($query instanceof PageableQueryInterface) {
            $pagers = $searchCriteria->getPagers();
            if (count($pagers) > 0) {
                $pager = array_pop($pagers);
                $query->setPage($pager->getAmount(), $pager->getPage());
            }
        }

        if ($query instanceof SortableQueryInterface) {
            foreach ($searchCriteria->getSorters() as $sorter) {
                $ordinal = DirectionEnum::getOrdinal(
                    (string) $sorter->getDirection()
                );
                $direction = [SortDirectionEnum::class, 'DIRECTION_' . $ordinal];
                $query->addSorter($sorter->getField(), $direction());
            }
        }
    }

    /**
     * Applies a filter group to the query.
     *
     * @param FilterableQueryInterface $query
     * @param FilterGroupInterface $filterGroup
     *
     * @return void
     */
    private function applyFilterGroup(
        FilterableQueryInterface $query,
        FilterGroupInterface $filterGroup
    ): void {
        $queryFilterGroup = new QueryFilterGroup();
        foreach ($filterGroup->getFilters() as $filter) {
            if ($filter instanceof FilterGroupInterface) {
                $this->applyFilterGroup($query, $filter);
                continue;
            }

            $ordinal = ComparatorEnum::getOrdinal((string) $filter->getComparator());
            $comparator = [SqlComparatorEnum::class, $ordinal];
            /** @var FilterInterface $filter */
            $queryFilterGroup->addFilter(
                new ComparatorFilter(
                    $filter->getField(),
                    $filter->getValue(),
                    $comparator()
                )
            );
        }

        $query->addFilterGroup($queryFilterGroup);
    }
}
