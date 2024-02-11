<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Psr\Container\ContainerInterface;

final class ConfigPanelFactory
{
    public function __invoke(ContainerInterface $container): ConfigPanel
    {
        return new ConfigPanel($container->get('config'));
    }
}
