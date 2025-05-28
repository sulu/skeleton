<?php

declare(strict_types=1);

use App\Kernel;

require \dirname(__DIR__) . '/bootstrap.php';

$appKernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']); // @phpstan-ignore-line argument.type
$appKernel->boot();

return $appKernel->getContainer();
