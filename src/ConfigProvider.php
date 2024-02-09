<?php

declare(strict_types=1);

namespace Axleus\DevTools;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'middleware_pipeline' => $this->getPipelineConfig(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Middleware\TracyDebuggerMiddleware::class => Middleware\TracyDebuggerMiddleware::class,
            ],
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