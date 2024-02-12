<?php

declare(strict_types=1);

namespace Axleus\DevTools\Middleware;

use Axleus\DevTools\Debug;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tracy\Debugger;

class RequestPanelMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Debug\RequestPanel $panel,
        private bool $debug
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->debug) {
            $this->panel->setData($request);
            Debugger::getBar()->addPanel($this->panel);
            Debugger::$showBar = true;
        }
        return $handler->handle($request);
    }
}
