<?php

declare(strict_types=1);

/**
 * This file is part of the Webware Traccio package.
 *
 * Copyright (c) 2026 Joey Smith <jsmith@webinertia.net>
 * and contributors.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webware\Traccio\Debug;

use Tracy\Helpers;

trait IBarPanelTrait
{
    protected array|object $data;

    protected string $id;

    public function getTab(): string
    {
        return Helpers::capture(function () {
            $data  = $this->data;
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

    // @param array|object $data
    // public function setData($data): void
    // {
    //     $this->data = $data;
    // }
}
