<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Laminas\Db\Adapter\Adapter;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use Tracy\IBarPanel;

final class SqlProfilerPanel implements IBarPanel
{
    use IBarPanelTrait;
    use TranslatorAwareTrait;

    public function __construct(
        private Adapter $data
    ) {
        $this->id = 'database';
    }
}
