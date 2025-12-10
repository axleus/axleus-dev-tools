<?php

declare(strict_types=1);

namespace Webware\DevTools;

use Tracy\Debugger;

trait TimerTrait
{
    private string $marker;

    public static function timer(string $marker): void
    {
        static $time   = [];
        $now           = hrtime(true);
        $delta         = isset($time[$marker]) ? $now - $time[$marker] : 0;
        $time[$marker] = $now;
        $elapsed       = $delta / 1e+6;
        if (isset($time[$marker]) && $elapsed > 0) {
            Debugger::barDump($marker . ' ' . $elapsed . ' ms');
        }
    }
}
