<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Laminas\I18n\Translator\TranslatorAwareTrait;
use Tracy\IBarPanel;

final class ConfigPanel implements IBarPanel
{
    use IBarPanelTrait;
    use TranslatorAwareTrait;

    public function __construct(
        private array $data
    ) {
        $this->id = 'config';
    }
}
