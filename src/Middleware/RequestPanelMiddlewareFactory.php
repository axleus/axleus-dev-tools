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

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Webware\Traccio\Debug;

final class RequestPanelMiddlewareFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RequestPanelMiddleware
    {
        /** @var bool */
        $debug = $container->get('config')['debug'];

        /** @var bool */
        $override = $container->get('config')['debug_overrides']['show_debugger_in_production'];

        return new RequestPanelMiddleware(
            $container->get(Debug\RequestPanel::class),
            $debug ? $debug : $override,
        );
    }
}
