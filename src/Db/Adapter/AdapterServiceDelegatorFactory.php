<?php

declare(strict_types=1);

namespace Webware\DevTools\Db\Adapter;

use PhpDb\Adapter\AdapterInterface;
use PhpDb\Adapter\Profiler\Profiler;
use Psr\Container\ContainerInterface;

final class AdapterServiceDelegatorFactory
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback): AdapterInterface
    {
        /** @var AdapterInterface $adapter */
        $adapter = $callback($container, $name);
        $adapter->setProfiler(new Profiler());
        return $adapter;
    }
}
