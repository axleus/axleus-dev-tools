<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Laminas\Db\Adapter\Adapter;
use Tracy\IBarPanel;

final class SqlProfilerPanel implements IBarPanel
{
    use IBarPanelTrait;

    public function __construct(
        private Adapter $data
    ) {
        $this->id = 'database';
    }
}
