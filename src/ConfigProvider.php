<?php

declare(strict_types=1);

namespace Axleus\DevTools;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Middleware\TracyDebuggerMiddleware::class => Middleware\TracyDebuggerMiddleware::class,
            ],
            'middleware_pipeline' => [
                [
                    'middleware' => [
                        Middleware\TracyDebuggerMiddleware::class,
                    ],
                    'priority' => 1,
                ],
            ],
        ];
    }
}