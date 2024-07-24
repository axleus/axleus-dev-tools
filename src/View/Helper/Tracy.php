<?php

declare(strict_types=1);

namespace Axleus\DevTools\View\Helper;

use Axleus\DevTools\TimerTrait;

final class Tracy
{
    use TimerTrait;

    public function __invoke()
    {
        return $this;
    }
}
