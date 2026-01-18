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

namespace Webware\Traccio\Container;

use Laminas\HttpHandlerRunner\RequestHandlerRunnerInterface;
use Mezzio\ApplicationPipeline;
use Mezzio\MiddlewareFactoryInterface;
use Mezzio\Router\RouteCollector;
use Mezzio\Router\RouteCollectorInterface;
use Psr\Container\ContainerInterface;
use Webware\Traccio\Application;

class ApplicationFactory
{
    public function __invoke(ContainerInterface $container): Application
    {
        return new Application(
            $container->get(MiddlewareFactoryInterface::class),
            $container->get(ApplicationPipeline::class),
            $container->has(RouteCollectorInterface::class)
                ? $container->get(RouteCollectorInterface::class)
                : $container->get(RouteCollector::class),
            $container->get(RequestHandlerRunnerInterface::class),
        );
    }
}
