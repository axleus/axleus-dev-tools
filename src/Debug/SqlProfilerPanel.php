<?php

declare(strict_types=1);

namespace Webware\DevTools\Debug;

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
