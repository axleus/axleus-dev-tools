<?php

declare(strict_types=1);

namespace Axleus\DevTools\Middleware;

use Axleus\DevTools\Debug;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class RequestPanelMiddlewareFactory
{
    /**
     *
     * @param ContainerInterface $container
     * @return TracyDebuggerMiddleware
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RequestPanelMiddleware
    {
        /** @var bool */
        $debug    = $container->get('config')['debug'];
        /** @var bool */
        $override = $container->get('config')['debug_overrides']['show_debugger_in_production'];

        return new RequestPanelMiddleware(
            $container->get(Debug\RequestPanel::class),
            $debug ? $debug : $override
        );
    }
}
