<?php

declare(strict_types=1);

/**
 * This file is part of the Webware Traccio package.
 *
 * Copyright (c) 2026 Joey Smith <jsmith@webinertia.net>
 * and contributors.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webware\Traccio;

use PhpDb\Adapter\AdapterInterface;
use Tracy\Debugger;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies'  => $this->getDependencies(),
            Debugger::class => $this->getTracyConfig(),
        ];
    }

    /**
     * @return TracyConfig
     */
    public function getTracyConfig(): array
    {
        return [
            'dumpTheme'  => 'dark',
            'keysToHide' => [
                'password',
                'pass',
                'secret',
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [
            'factories' => [
                Debug\ConfigPanel::class                  => Debug\ConfigPanelFactory::class,
                Debug\RequestPanel::class                 => Debug\RequestPanelFactory::class,
                Debug\SqlProfilerPanel::class             => Debug\SqlProfilerPanelFactory::class,
                Debug\RoutesPanel::class                  => Debug\RoutesPanelFactory::class,
                Middleware\TracyDebuggerMiddleware::class => Middleware\TracyDebuggerMiddlewareFactory::class,
                Middleware\RequestPanelMiddleware::class  => Middleware\RequestPanelMiddlewareFactory::class,
            ],
            // Add this to the application's config to enable DB profiling
            // 'delegators' => [
            //     AdapterInterface::class => [
            //         PhpDb\ProfilingDelegator::class,
            //     ],
            // ],
        ];
    }
}
