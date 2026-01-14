<?php

declare(strict_types=1);

/*
 * This file is part of the Webware Traccio component.
 *
 * Copyright (c) 2023-2026 Joey Smith <jsmith@webinertia.net>
 * and contributors.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webware\Traccio\Middleware;

use PhpDb\Adapter\Profiler\ProfilerInterface;
use Psr\Container\ContainerInterface;
use Tracy\Debugger;
use Webware\Traccio\Debug;

final class TracyDebuggerMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): TracyDebuggerMiddleware
    {
        $hasProfiler = $container->has(ProfilerInterface::class);
        return new TracyDebuggerMiddleware(
            $container->get('config')['debug'],
            $container->get('config')[Debugger::class],
            $hasProfiler,
            $container->has(Debug\ConfigPanel::class) ? $container->get(Debug\ConfigPanel::class) : null,
            $hasProfiler ? $container->get(Debug\SqlProfilerPanel::class) : null,
            $container->has(Debug\RoutesPanel::class) ? $container->get(Debug\RoutesPanel::class) : null,
        );
    }
}
