<?php

declare(strict_types=1);

namespace Webware\Traccio\Debug;

use PhpDb\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class SqlProfilerPanelFactory
{
    public function __invoke(ContainerInterface $container): SqlProfilerPanel
    {
        return new SqlProfilerPanel($container->get(AdapterInterface::class));
    }
}
