<?php

declare(strict_types=1);

namespace Axleus\DevTools;

use Tracy\Debugger;

use function number_format;

trait TimerTrait
{
    private ?string $marker;
    /**
     *
     * @param null|string $marker defaults to 'total-runtime'
     * @param null|string $tag Is appended to the passed $marker
     * @return void
     */
    public function stopWatch(?string $marker = null, ?string $tag = '')
    {
        if (null !== $marker) {
            $this->marker = $marker;
        }
        if (!empty($tag)) {
            $tag = '.' . $tag;
        }
        $marker = $this->marker === $marker ? $this->marker : 'total-runtime';
        $time = number_format(Debugger::timer($marker) * 1000, 5, '.', "\u{202f}") . ' ms';
        Debugger::barDump($time, $marker . $tag);
    }

    public function start(?string $marker): void
    {
        $this->marker = $marker;
        Debugger::timer($this->marker);
    }

}
