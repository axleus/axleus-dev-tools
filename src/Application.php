<?php

declare(strict_types=1);

namespace Axleus\DevTools;

use Axleus\DevTools\TimerTrait;
use Mezzio\Application as MezzioApplication;

class Application extends MezzioApplication
{
    use TimerTrait;

    public function run(): void
    {
        parent::run();
        //$this->stopWatch(tag: 'post-emit');
    }
}
