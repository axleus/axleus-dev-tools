<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Laminas\Db\Adapter\Adapter;
use Tracy\IBarPanel;
use Tracy\Helpers;

final class SqlProfiler implements IBarPanel
{
    public function __construct(
        private string $id,
        private Adapter $adapter
    ) {
    }

    public function getTab(): string
    {
        return Helpers::capture(function () {
			$profiler = $this->adapter->getProfiler();
			require __DIR__ . "/panels/{$this->id}.tab.phtml";
		});
    }

    public function getPanel(): string
    {
        return Helpers::capture(function () {
			$profiler = $this->adapter->getProfiler();
			require __DIR__ . "/panels/{$this->id}.panel.phtml";
		});
    }

}
