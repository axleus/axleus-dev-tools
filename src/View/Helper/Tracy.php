<?php

declare(strict_types=1);

namespace Axleus\DevTools\View\Helper;

use Tracy\Debugger;

final class Tracy
{
    public function __invoke()
    {
        return $this;
    }

    public function stopWatch(?string $marker = null)
    {
        $marker = $marker ?? 'total-runtime';
        Debugger::barDump([$marker => Debugger::timer($marker)]);
    }
}
