<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class SqlProfilerFactory
{
    public function __invoke(ContainerInterface $container): SqlProfiler
    {
        return new SqlProfiler('Database', $container->get(AdapterInterface::class));
    }
}
