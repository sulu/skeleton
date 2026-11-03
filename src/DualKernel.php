<?php

declare(strict_types=1);

namespace App;

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Sulu\Component\HttpKernel\SuluKernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;

/**
 * Holds the admin and the website kernel and hands every request to the one responsible for it.
 *
 * The context of a SuluKernel is fixed when it is created. A long-running process like the
 * FrankenPHP worker therefore needs both kernels up front, instead of deciding in the front controller.
 */
class DualKernel implements HttpKernelInterface, TerminableInterface
{
    private Kernel $adminKernel;

    private Kernel $websiteKernel;

    private readonly bool $useHttpCache;

    public function __construct(string $environment, bool $debug)
    {
        $this->adminKernel = new Kernel($environment, $debug, SuluKernel::CONTEXT_ADMIN);
        $this->websiteKernel = new Kernel($environment, $debug, SuluKernel::CONTEXT_WEBSITE);

        // Set this to false if you want to use the "varnish" http
        // caching strategy. See http://sulu.readthedocs.org/en/latest/cookbook/caching-with-varnish.html
        $this->useHttpCache = 'dev' !== $environment;
    }

    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        return $this->getKernel($request)->handle($request, $type, $catch);
    }

    public function terminate(Request $request, Response $response): void
    {
        $kernel = $this->getKernel($request);

        if ($kernel instanceof TerminableInterface) {
            $kernel->terminate($request, $response);
        }
    }

    /**
     * Used by the FrankenPHP runtime when FRANKENPHP_RESET_KERNEL is set to start every request with fresh kernels.
     */
    public function __clone()
    {
        $this->adminKernel = clone $this->adminKernel;
        $this->websiteKernel = clone $this->websiteKernel;
    }

    private function getKernel(Request $request): HttpKernelInterface
    {
        if (\preg_match('/^\/admin(\/|$)/', $request->getPathInfo())) {
            return $this->adminKernel;
        }

        return $this->useHttpCache ? $this->websiteKernel->getHttpCache() : $this->websiteKernel;
    }
}
