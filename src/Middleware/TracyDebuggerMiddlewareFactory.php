<?php

declare(strict_types=1);

namespace Webware\DevTools\Middleware;

use Webware\DevTools\ConfigProvider;
use Webware\DevTools\Debug;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tracy\Debugger;

final class TracyDebuggerMiddlewareFactory
{
    /**
     *
     * @param ContainerInterface $container
     * @return TracyDebuggerMiddleware
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): TracyDebuggerMiddleware
    {
        /** @var bool */
        $debug    = [
            'debug' => $container->get('config')['debug']]
                + $container->get('config')[ConfigProvider::class][Debugger::class];

        return new TracyDebuggerMiddleware(
            $container->get(Debug\ConfigPanel::class),
            $container->get(Debug\SqlProfilerPanel::class),
            $container->get(Debug\RoutesPanel::class),
            $debug,
        );
    }
}
