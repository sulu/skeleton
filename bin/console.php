<?php

declare(strict_types=1);

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;

$vendorDir = exec('composer config vendor-dir');

if (!\is_dir($vendorDir)) {
    throw new \LogicException('Dependencies are missing from vendor directory "'.$vendorDir.'". Try running "composer install".');
}

if (!\is_file($vendorDir.'/autoload_runtime.php')) {
    throw new \LogicException('Symfony Runtime is missing. Try running "composer require symfony/runtime".');
}

require_once $vendorDir.'/autoload_runtime.php';

if (!isset($suluContext)) {
    $suluContext = Kernel::CONTEXT_ADMIN;
}

return function (array $context) use ($suluContext) {
    $kernel = new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG'], $suluContext);

    return new Application($kernel);
};
