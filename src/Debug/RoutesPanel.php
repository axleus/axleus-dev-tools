<?php

declare(strict_types=1);

namespace Webware\Traccio\Debug;

use Mezzio\Router\RouteCollector;
use Tracy\IBarPanel;

final class RoutesPanel implements IBarPanel
{
    use IBarPanelTrait;

    public function __construct(
        private RouteCollector $data,
    ) {
        $this->id = 'routes';
    }
}
