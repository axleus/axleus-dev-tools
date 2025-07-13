<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Tracy\IBarPanel;
use Mezzio\Router\RouteCollector;

final class RoutesPanel implements IBarPanel
{
    use IBarPanelTrait;

    public function __construct(
        private RouteCollector $data
    ) {
        $this->id = 'routes';
    }
}
