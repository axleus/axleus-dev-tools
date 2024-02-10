<?php

declare(strict_types=1);

namespace Axleus\DevTools;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'laminas-cli'  => $this->getConsoleConfig(),
            'middleware_pipeline' => $this->getPipelineConfig(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'factories' => [
                Console\Command\DbConfigCommand::class => Console\Command\Factory\DbConfigCommandFactory::class,
                Console\Command\BuildDbCommand::class => Console\Command\Factory\BuildDbCommandFactory::class,
            ],
            'invokables' => [
                Middleware\TracyDebuggerMiddleware::class => Middleware\TracyDebuggerMiddleware::class,
            ],
        ];
    }

    public function getConsoleConfig(): array
    {
        return [
            'commands' => [
                'axleus:db:write-config' => Console\Command\DbConfigCommand::class,
                'axleus:db:create'       => Console\Command\BuildDbCommand::class,
            ],
            // 'chains'   => [
            //     Console\Command\DbConfigCommand::class => [
            //         Console\Command\BuildDbCommand::class => [],
            //     ],
            // ],
        ];
    }

    public function getPipelineConfig(): array
    {
        return [
            [
                'middleware' => [
                    Middleware\TracyDebuggerMiddleware::class,
                ],
                'priority' => 12000,
            ],
        ];
    }
}