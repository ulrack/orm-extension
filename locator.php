<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

use Ulrack\OrmExtension\Common\UlrackOrmExtensionPackage;
use GrizzIt\Configuration\Component\Configuration\PackageLocator;

PackageLocator::registerLocation(
    __DIR__,
    UlrackOrmExtensionPackage::PACKAGE_NAME
);
