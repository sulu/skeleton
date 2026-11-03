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

use App\DualKernel;
use Symfony\Component\HttpFoundation\Request;

\defined('SULU_MAINTENANCE') || \define('SULU_MAINTENANCE', \getenv('SULU_MAINTENANCE') ?: false);

// maintenance mode
if (SULU_MAINTENANCE) {
    // the maintenance page is decided per request by the client ip, which a FrankenPHP worker does not know while booting
    if (\filter_var($_SERVER['FRANKENPHP_WORKER'] ?? false, \FILTER_VALIDATE_BOOL)) {
        throw new \LogicException('SULU_MAINTENANCE is not supported in FrankenPHP worker mode, show the maintenance page with the web server instead.');
    }

    $maintenanceFilePath = __DIR__ . '/maintenance.php';
    // show maintenance mode and exit if no allowed IP is met
    if (require $maintenanceFilePath) {
        exit;
    }
}

require_once \dirname(__DIR__) . '/vendor/autoload_runtime.php';

// When using the HttpCache, you need to call the method in your front controller
// instead of relying on the configuration parameter
// https://symfony.com/doc/6.4/reference/configuration/framework.html#http-method-override
Request::enableHttpMethodParameterOverride();

return static function (array $context): DualKernel {
    if ($trustedProxies = $context['TRUSTED_PROXIES'] ?? false) {
        Request::setTrustedProxies(\explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO); // @phpstan-ignore-line argument.type
    }

    if ($trustedHosts = $context['TRUSTED_HOSTS'] ?? false) {
        Request::setTrustedHosts([$trustedHosts]);
    }

    return new DualKernel($context['APP_ENV'], (bool) $context['APP_DEBUG']); // @phpstan-ignore-line argument.type
};
