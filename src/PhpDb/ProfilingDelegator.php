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

namespace Webware\Traccio\PhpDb;

use PhpDb\Adapter\Adapter;
use PhpDb\Adapter\AdapterInterface;
use PhpDb\Adapter\Profiler\Profiler;
use Psr\Container\ContainerInterface;

final class ProfilingDelegator
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback): AdapterInterface
    {
        /** @var Adapter&AdapterInterface $adapter */
        $adapter = $callback($container, $name);
        $adapter->setProfiler(new Profiler());

        return $adapter;
    }
}
