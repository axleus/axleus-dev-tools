<?php

declare(strict_types=1);

namespace Axleus\DevTools\Middleware;

use Axleus\DevTools\Debug;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tracy\Debugger;

class TracyDebuggerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Debug\ConfigPanel $configPanel,
        private Debug\SqlProfilerPanel $sqlProfilerPanel,
        private Debug\RoutesPanel $routesPanel,
        private bool $debug
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->debug) {
            Debugger::getBar()->addPanel($this->sqlProfilerPanel);
            Debugger::getBar()->addPanel($this->configPanel);
            Debugger::getBar()->addPanel($this->routesPanel);
            Debugger::enable();
            Debugger::$showBar = false;
        }
        return $handler->handle($request);
    }
}
