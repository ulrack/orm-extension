<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Factory;

use Ulrack\Services\Common\ServiceFactoryInterface;
use Ulrack\OrmExtension\Common\Model\ModelInterface;
use GrizzIt\ObjectFactory\Common\ObjectFactoryInterface;
use Ulrack\OrmExtension\Common\Factory\ModelFactoryInterface;

class ModelFactory implements ModelFactoryInterface
{
    /**
     * Contains the service factory.
     *
     * @var ServiceFactoryInterface
     */
    private $serviceFactory;

    /**
     * Constructor.
     *
     * @param ServiceFactoryInterface $serviceFactory
     */
    public function __construct(ServiceFactoryInterface $serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
    }

    /**
     * Creates a model based on the input array.
     *
     * @param string $modelService
     * @param array $data
     *
     * @return ModelInterface
     */
    public function create(string $modelService, array $data): ModelInterface
    {
        return $this->serviceFactory->create(
            $modelService,
            ['data' => $data]
        );
    }
}
