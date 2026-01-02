<?php

declare(strict_types=1);

namespace Webware\Traccio\Middleware;

use Psr\Container\ContainerInterface;
use Tracy\Debugger;
use Webware\Traccio\Debug;

use function class_exists;

final class TracyDebuggerMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): TracyDebuggerMiddleware
    {
        $sqlProfilerPanel = null;

        if (class_exists(\PhpDb\Adapter\AdapterInterface::class)) {
            // Ensure the SqlProfilerPanel is registered
            $sqlProfilerPanel = $container->get(Debug\SqlProfilerPanel::class);
        }

        return new TracyDebuggerMiddleware(
            $container->get('config')['debug'],
            $container->get('config')[Debugger::class],
            $container->has(Debug\ConfigPanel::class) ? $container->get(Debug\ConfigPanel::class) : null,
            $sqlProfilerPanel,
            $container->has(Debug\RoutesPanel::class) ? $container->get(Debug\RoutesPanel::class) : null,
        );
    }
}
