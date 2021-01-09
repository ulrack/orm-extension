<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\OrmExtension\Common\Model;

use GrizzIt\Storage\Common\StorageInterface;

interface ModelInterface extends StorageInterface
{
    /**
     * Converts the content from the model to an array.
     *
     * @return array
     */
    public function toArray(): array;
}
