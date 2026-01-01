<?php

declare(strict_types=1);

namespace Webware\Traccio\PhpDb;

use PhpDb\Adapter\AdapterInterface;
use PhpDb\Adapter\Profiler\Profiler;
use Psr\Container\ContainerInterface;

final class ProfilingDelegator
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback): AdapterInterface
    {
        /** @var AdapterInterface $adapter */
        $adapter = $callback($container, $name);
        $adapter->setProfiler(new Profiler());

        return $adapter;
    }
}
