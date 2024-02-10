<?php

declare(strict_types=1);

namespace Axleus\DevTools\Console\Command\Factory;

use Axleus\DevTools\Console\Command\DbConfigCommand;
use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class DbConfigCommandFactory
{
    public function __invoke(ContainerInterface $container): DbConfigCommand
    {
        return new DbConfigCommand(
            $container->get(AdapterInterface::class),
            $container->get('config')
        );
    }
}
