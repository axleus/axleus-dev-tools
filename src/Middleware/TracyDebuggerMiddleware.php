<?php

declare(strict_types=1);

namespace Axleus\DevTools\Middleware;

use Axleus\DevTools\Debug\SqlProfiler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tracy\Debugger;

class TracyDebuggerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private SqlProfiler $sqlProfiler,
        private bool $debug
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->debug) {
            Debugger::getBar()->addPanel($this->sqlProfiler);
            Debugger::enable();
        }
        return $handler->handle($request);
    }
}
