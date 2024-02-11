<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class SqlProfilerPanelFactory
{
    public function __invoke(ContainerInterface $container): SqlProfilerPanel
    {
        return new SqlProfilerPanel($container->get(AdapterInterface::class));
    }
}
