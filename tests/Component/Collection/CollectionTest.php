<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Tests\Component\Collection;

use Generator;
use PHPUnit\Framework\TestCase;
use Ulrack\OrmExtension\Common\Model\ModelInterface;
use Ulrack\OrmExtension\Component\Collection\Collection;

/**
 * @coversDefaultClass \Ulrack\OrmExtension\Component\Collection\Collection
 */
class CollectionTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getAllItems
     * @covers ::count
     * @covers ::current
     * @covers ::key
     * @covers ::next
     * @covers ::rewind
     * @covers ::valid
     *
     * @return void
     */
    public function testComponent(): void
    {
        $subject = new Collection($this->getGenerator(), 10);
        $count = 0;
        foreach ($subject as $key => $model) {
            $this->assertIsInt($key);
            $this->assertInstanceOf(ModelInterface::class, $model);
            $count++;
            if ($count === 5) {
                break;
            }
        }

        $result = $subject->getAllItems();

        $this->assertEquals(10, count($result));
        $this->assertEquals(10, $subject->count());
    }

    /**
     * Creates a generator for the unit tests.
     *
     * @return Generator
     */
    private function getGenerator(): Generator
    {
        for ($i = 0; $i < 10; $i++) {
            yield $this->createMock(ModelInterface::class);
        }
    }
}
