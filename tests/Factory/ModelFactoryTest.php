<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Ulrack\OrmExtension\Factory\ModelFactory;
use Ulrack\OrmExtension\Common\Model\ModelInterface;
use GrizzIt\Services\Common\Factory\ServiceFactoryInterface;

/**
 * @coversDefaultClass \Ulrack\OrmExtension\Factory\ModelFactory
 */
class ModelFactoryTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::create
     *
     * @return void
     */
    public function testCreate(): void
    {
        $serviceFactory = $this->createMock(ServiceFactoryInterface::class);
        $model = $this->createMock(ModelInterface::class);
        $subject = new ModelFactory($serviceFactory);
        $serviceFactory->expects(static::once())
            ->method('create')
            ->with('services.model', ['data' => ['foo']])
            ->willReturn($model);

        $subject->create('services.model', ['foo']);
    }
}
