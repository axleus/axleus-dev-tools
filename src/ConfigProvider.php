<?php

declare(strict_types=1);

namespace Webware\Traccio;

use Mezzio\Application;
use PhpDb\Adapter\AdapterInterface;
use Tracy\Debugger;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies'  => $this->getDependencies(),
            'laminas-cli'   => $this->getConsoleConfig(),
            'view_helpers'  => $this->getViewHelpers(),
            Debugger::class => $this->getTracyConfig(),
        ];
    }

    public function getTracyConfig(): array
    {
        return [
            'dumpTheme'  => 'dark',
            'keysToHide' => [
                'password',
                'pass',
                'secret',
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
            // Add this to the application's config to enable DB profiling
            // 'delegators' => [
            //     AdapterInterface::class => [
            //         PhpDb\ProfilingDelegator::class,
            //     ],
            // ],
        ];
    }

    public function getConsoleConfig(): array
    {
        return [
            'commands' => [
                'Webware:db:write-config' => Console\Command\DbConfigCommand::class,
                'Webware:db:create'       => Console\Command\BuildDbCommand::class,
            ],
        ];
    }

    public function getViewHelpers(): array
    {
        return [
            'aliases'    => [
                'timer'     => View\Helper\StopWatch::class,
                'stopWatch' => View\Helper\StopWatch::class,
            ],
            'invokables' => [
                View\Helper\StopWatch::class => View\Helper\StopWatch::class,
            ],
        ];
    }
}
