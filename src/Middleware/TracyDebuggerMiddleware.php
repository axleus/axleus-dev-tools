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
        private bool $debug,
        private array $tracyConfig,
        private ?Debug\ConfigPanel $configPanel,
        private ?Debug\SqlProfilerPanel $sqlProfilerPanel,
        private ?Debug\RoutesPanel $routesPanel,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->debug) {
            foreach ($this->tracyConfig as $key => $value) {
                Debugger::${$key} = $value;
            }
            if (class_exists(\PhpDb\Adapter\AdapterInterface::class)) {
                Debugger::getBar()->addPanel($this->sqlProfilerPanel);
            }
            Debugger::getBar()->addPanel($this->configPanel);
            Debugger::getBar()->addPanel($this->routesPanel);

        }
        return $handler->handle($request);
    }
}
