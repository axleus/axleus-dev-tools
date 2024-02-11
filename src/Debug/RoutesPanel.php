<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Tracy\IBarPanel;

final class RoutesPanel implements IBarPanel
{
    use IBarPanelTrait;

    public function __construct(
        private array $data
    ) {
        $this->id = 'routes';
    }
}
