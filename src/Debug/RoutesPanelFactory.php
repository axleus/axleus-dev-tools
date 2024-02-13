<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Psr\Container\ContainerInterface;
use Mezzio\Router\RouteCollectorInterface;

final class RoutesPanelFactory
{
    public function __invoke(ContainerInterface $container): RoutesPanel
    {
        return new RoutesPanel($container->get(RouteCollectorInterface::class));
    }
}
