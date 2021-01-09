<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Common\Applicator;

use Ulrack\Dbal\Common\QueryInterface;
use GrizzIt\Search\Common\SearchCriteriaInterface;

interface ApplicatorInterface
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
    ): void;
}
