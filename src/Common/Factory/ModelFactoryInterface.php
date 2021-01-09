<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Common\Factory;

use Ulrack\OrmExtension\Common\Model\ModelInterface;

interface ModelFactoryInterface
{
    /**
     * Creates a model based on the input array.
     *
     * @param string $modelClass
     * @param array $data
     *
     * @return ModelInterface
     */
    public function create(string $modelClass, array $data): ModelInterface;
}
