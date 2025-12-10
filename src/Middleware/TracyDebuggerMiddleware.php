<?php

declare(strict_types=1);

namespace Webware\DevTools\Middleware;

use Webware\DevTools\Debug;
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
        private array $debugConfig
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->debugConfig['debug']) {
            unset($this->debugConfig['debug']);
            // foreach ($this->debugConfig as $key => $value) {
            //     if ($key === 'strictMode') {
            //         continue;
            //     }
            //     Debugger::${$key} = $value;
            // }
            // Debugger::$strictMode = $this->debugConfig['strictMode'];
            // Debugger::$dumpTheme  = $this->debugConfig['dumpTheme'];
            Debugger::getBar()->addPanel($this->sqlProfilerPanel);
            Debugger::getBar()->addPanel($this->configPanel);
            Debugger::getBar()->addPanel($this->routesPanel);

        }
        return $handler->handle($request);
    }
}
