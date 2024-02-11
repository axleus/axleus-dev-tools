<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Tracy\IBarPanel;
use Tracy\Helpers;

final class ConfigPanel implements IBarPanel
{
    private string $id = 'config';

    public function __construct(
        private array $config
    ) {
    }

    public function getTab(): string
    {
        return Helpers::capture(function () {
            $config = $this->config;
            require __DIR__ . "/panels/{$this->id}.tab.phtml";
        });
    }

    public function getPanel(): string
    {
        return Helpers::capture(function () {
            $config = $this->config;
            require __DIR__ . "/panels/{$this->id}.panel.phtml";
        });
    }
}
