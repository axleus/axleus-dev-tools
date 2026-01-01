<?php

declare(strict_types=1);

namespace Webware\Traccio\Debug;

use Mezzio\Router\RouteCollectorInterface;
use Psr\Container\ContainerInterface;

final class RoutesPanelFactory
{
    public function __invoke(ContainerInterface $container): RoutesPanel
    {
        return new RoutesPanel($container->get(RouteCollectorInterface::class));
    }
}
