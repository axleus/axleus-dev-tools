<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use PhpDb\Adapter\AdapterInterface;
use Tracy\IBarPanel;

final class SqlProfilerPanel implements IBarPanel
{
    use IBarPanelTrait;

    public function __construct(
        private AdapterInterface $data
    ) {
        $this->id = 'database';
    }
}
