<?php

declare(strict_types=1);

namespace Axleus\DevTools\Console\Command\Factory;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Axleus\DevTools\Console\Command\BuildDbCommand;

class BuildDbCommandFactory implements FactoryInterface
{
    /** @param string $requestedName */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): BuildDbCommand
    {
        return new $requestedName(
            $container->get(AdapterInterface::class),
            $container->get('config'),
        );
    }
}
