<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Tracy\IBarPanel;

final class ConfigPanel implements IBarPanel
{
    public function __construct(
        private array $config
    ) {
    }

    public function getTab(): string
    {

    }

    public function getPanel(): string
    {

    }
}
