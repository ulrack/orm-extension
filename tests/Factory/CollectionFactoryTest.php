<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Tests\Factory;

use Generator;
use ArrayIterator;
use PHPUnit\Framework\TestCase;
use Ulrack\OrmExtension\Factory\CollectionFactory;
use Ulrack\OrmExtension\Tests\Mock\MockQueryResult;
use Ulrack\Services\Common\ServiceFactoryInterface;
use Ulrack\OrmExtension\Common\Model\ModelInterface;
use Ulrack\OrmExtension\Common\Factory\ModelFactoryInterface;
use Ulrack\OrmExtension\Common\Collection\CollectionInterface;

/**
 * @coversDefaultClass \Ulrack\OrmExtension\Factory\CollectionFactory
 */
class CollectionFactoryTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::create
     * @covers ::getGenerator
     *
     * @return void
     */
    public function testCreate(): void
    {
        $modelFactory = $this->createMock(ModelFactoryInterface::class);
        $serviceFactory = $this->createMock(ServiceFactoryInterface::class);
        $result = $this->createMock(MockQueryResult::class);
        $subject = new CollectionFactory($modelFactory, $serviceFactory);

        $result->expects(static::once())
            ->method('getIterator')
            ->willReturn(new ArrayIterator([['foo' => 'bar']]));

        $result->expects(static::once())
            ->method('count')
            ->willReturn(1);

        $modelFactory->expects(static::once())
            ->method('create')
            ->with('services.model', ['foo' => 'bar'])
            ->willReturn($this->createMock(ModelInterface::class));

        $serviceFactory->expects(static::once())
            ->method('create')
            ->will(
                $this->returnCallback(
                    function (string $service, array $parameters) {
                        $this->assertEquals('services.collection', $service);
                        $generator = $parameters['modelGenerator'];
                        $this->assertInstanceOf(Generator::class, $generator);
                        foreach ($generator as $model) {
                            $this->assertInstanceOf(
                                ModelInterface::class,
                                $model
                            );
                        }

                        return $this->createMock(CollectionInterface::class);
                    }
                )
            );

        $this->assertInstanceOf(
            CollectionInterface::class,
            $subject->create(
                'services.collection',
                'services.model',
                $result
            )
        );
    }
}
