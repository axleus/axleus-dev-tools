<?php

declare(strict_types=1);

namespace Axleus\DevTools\View\Helper;

use Axleus\DevTools\TimerTrait;

class StopWatch
{
    use TimerTrait;

    public function __invoke(?string $marker = null, ?string $tag = null): self
    {
        if ($marker !== null) {
            $this->stopWatch($marker, $tag);
        }
        return $this;
    }
}
