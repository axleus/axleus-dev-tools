<?php

declare(strict_types=1);

namespace Axleus\DevTools;

use Laminas\Db\Adapter\AdapterInterface;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'debug_overrides' => [
                'show_debugger_in_production' => false, // in case we want to see timings etc in production
            ],
            'dependencies' => $this->getDependencies(),
            'laminas-cli'  => $this->getConsoleConfig(),
            'middleware_pipeline' => $this->getPipelineConfig(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'factories' => [
                Console\Command\DbConfigCommand::class    => Console\Command\Factory\DbConfigCommandFactory::class,
                Console\Command\BuildDbCommand::class     => Console\Command\Factory\BuildDbCommandFactory::class,
                Debug\ConfigPanel::class                  => Debug\ConfigPanelFactory::class,
                Debug\SqlProfilerPanel::class             => Debug\SqlProfilerPanelFactory::class,
                Middleware\TracyDebuggerMiddleware::class => Middleware\TracyDebuggerMiddlewareFactory::class,
                Middleware\RequestPanelMiddleware::class  => Middleware\RequestPanelMiddlewareFactory::class,
            ],
            'delegators' => [
                AdapterInterface::class => [
                    Db\Adapter\AdapterServiceDelegatorFactory::class,
                ],
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
            [
                'middleware' => [
                    Middleware\RequestPanelMiddleware::class,
                ],
                'priority' => 1,
            ],
        ];
    }
}