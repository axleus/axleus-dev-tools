<?php

declare(strict_types=1);

namespace Axleus\DevTools;

use Tracy\Debugger;

use function number_format;

trait TimerTrait
{
    private const TOTAL_RUNTIME = 'total-runtime';

    private ?string $marker;
    /**
     *
     * @param null|string $marker defaults to 'total-runtime'
     * @param null|string $tag Is appended to the passed $marker
     * @return void
     */
    public function stopWatch(string $marker, ?string $tag = '')
    {
        if (null !== $marker) {
            $this->marker = $marker;
        }
        if (!empty($tag)) {
            $tag = '.' . $tag;
        }
        $marker = $this->marker === $marker ? $this->marker : static::TOTAL_RUNTIME;
        $time = number_format(Debugger::timer($marker) * 1000, 5, '.', "\u{202f}") . ' ms';
        Debugger::barDump($time, $marker . $tag);
    }

    public static function timer(string $marker): void
    {
        static $time = [];
        $now   = hrtime(true);
        $delta = isset($time[$marker]) ? $now - $time[$marker] : 0;
        $time[$marker] = $now;
        $elapsed = $delta / 1e+6;
        if (isset($time[$marker]) && $elapsed > 0) {
            Debugger::barDump($marker . ' ' . $elapsed . ' ms');
        }
    }
}
