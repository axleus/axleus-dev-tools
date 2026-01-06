<?php

declare(strict_types=1);

/*
 * This file is part of the Webware Traccio component.
 *
 * Copyright (c) 2023-2026 Joey Smith <jsmith@webinertia.net>
 * and contributors.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webware\Traccio\Middleware;

use PhpDb\Adapter\AdapterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tracy\Debugger;
use Webware\Traccio\Debug;

class TracyDebuggerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private bool $debug,
        private array $tracyConfig,
        private ?Debug\ConfigPanel $configPanel,
        private ?Debug\SqlProfilerPanel $sqlProfilerPanel,
        private ?Debug\RoutesPanel $routesPanel,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->debug) {
            foreach ($this->tracyConfig as $key => $value) {
                Debugger::${$key} = $value;
            }

            if (class_exists(AdapterInterface::class)) {
                Debugger::getBar()->addPanel($this->sqlProfilerPanel);
            }

            if ($this->configPanel) {
                Debugger::getBar()->addPanel($this->configPanel);
            }

            if ($this->routesPanel) {
                Debugger::getBar()->addPanel($this->routesPanel);
            }
        }

        return $handler->handle($request);
    }
}
