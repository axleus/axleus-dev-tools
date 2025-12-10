<?php

declare(strict_types=1);

namespace Webware\DevTools\Debug;

use Tracy\IBarPanel;

final class ConfigPanel implements IBarPanel
{
    use IBarPanelTrait;

    public function __construct(
        private array $data
    ) {
        $this->id = 'config';
    }
}
