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
}
