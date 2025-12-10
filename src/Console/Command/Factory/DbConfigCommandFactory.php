<?php

declare(strict_types=1);

namespace Webware\DevTools\Console\Command\Factory;

use Webware\DevTools\Console\Command\DbConfigCommand;
use PhpDb\Adapter\AdapterInterface;
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
