<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Component\Model;

use GrizzIt\Storage\Component\ObjectStorage;
use Ulrack\OrmExtension\Common\Model\ModelInterface;

abstract class AbstractModel extends ObjectStorage implements ModelInterface
{
    /**
     * Converts the content from the model to an array.
     *
     * @return array
     */
    abstract public function toArray(): array;
}
