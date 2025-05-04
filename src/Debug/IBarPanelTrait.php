<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Tracy\Helpers;

trait IBarPanelTrait
{
    protected string $id;

    public function getTab(): string
    {
        return Helpers::capture(function () {
            $data = $this->data;
            $title = $this->id;
            require __DIR__ . "/panels/{$this->id}.tab.phtml";
        });
    }

    public function getPanel(): string
    {
        return Helpers::capture(function () {
            $data = $this->data;
            require __DIR__ . "/panels/{$this->id}.panel.phtml";
        });
    }

    public function setData($data): void
    {
        $this->data = $data;
    }
}
