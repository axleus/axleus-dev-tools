<?php

declare(strict_types=1);

namespace Webware\DevTools;

use PhpDb\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mezzio\Application;
use Tracy\Debugger;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'debug_overrides'     => [
                'show_debugger_in_production' => false, // in case we want to see timings etc in production
            ],
            'dependencies'        => $this->getDependencies(),
            'laminas-cli'         => $this->getConsoleConfig(),
            //'middleware_pipeline' => $this->getPipelineConfig(),
            'view_helpers'        => $this->getViewHelpers(),
            static::class         => $this->getWebwareConfig(),
        ];
    }

    public function getWebwareConfig(): array
    {
        return [
            Debugger::class => [
                'dumpTheme'      => 'dark',
                'keysToHide'     => [
                    'password',
                    'secret',
                ]
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [
            'factories' => [
                Application::class                        => Container\ApplicationFactory::class,
                Console\Command\DbConfigCommand::class    => Console\Command\Factory\DbConfigCommandFactory::class,
                Console\Command\BuildDbCommand::class     => Console\Command\Factory\BuildDbCommandFactory::class,
                Debug\ConfigPanel::class                  => Debug\ConfigPanelFactory::class,
                Debug\RequestPanel::class                 => Debug\RequestPanelFactory::class,
                Debug\SqlProfilerPanel::class             => Debug\SqlProfilerPanelFactory::class,
                Debug\RoutesPanel::class                  => Debug\RoutesPanelFactory::class,
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
                'Webware:db:write-config' => Console\Command\DbConfigCommand::class,
                'Webware:db:create'       => Console\Command\BuildDbCommand::class,
            ],
            // 'chains'   => [
            //     Console\Command\DbConfigCommand::class => [
            //         Console\Command\BuildDbCommand::class => [],
            //     ],
            // ],
        ];
    }

    public function getViewHelpers(): array
    {
        return [
            'aliases' => [
                'timer'     => View\Helper\StopWatch::class,
                'stopWatch' => View\Helper\StopWatch::class,
            ],
            'factories' => [
                View\Helper\StopWatch::class => InvokableFactory::class,
            ],
        ];
    }

    // public function getPipelineConfig(): array
    // {
    //     // return [
    //     //     [
    //     //         'middleware' => [
    //     //             Middleware\TracyDebuggerMiddleware::class,
    //     //         ],
    //     //         'priority' => 12000,
    //     //     ],
    //     //     [
    //     //         'middleware' => [
    //     //             Middleware\RequestPanelMiddleware::class,
    //     //         ],
    //     //         'priority' => 1,
    //     //     ],
    //     // ];
    // }
}