<?php

declare(strict_types=1);

namespace Axleus\DevTools\Db\Adapter;

use PhpDb\Adapter\AdapterInterface;
use PhpDb\Adapter\Profiler\Profiler;
use Psr\Container\ContainerInterface;

final class AdapterServiceDelegatorFactory
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback): AdapterInterface
    {
        $adapter = $callback();
        $adapter->setProfiler(new Profiler());
        return $adapter;
    }
}
