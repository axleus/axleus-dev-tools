<?php

declare(strict_types=1);

namespace Webware\DevTools\Debug;

use Psr\Container\ContainerInterface;

final class RequestPanelFactory
{
    public function __invoke(ContainerInterface $container): RequestPanel
    {
        return new RequestPanel();
    }
}
