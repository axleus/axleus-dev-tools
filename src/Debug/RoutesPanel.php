<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Tracy\IBarPanel;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use Mezzio\Router\RouteCollector;

final class RoutesPanel implements IBarPanel
{
    use IBarPanelTrait, TranslatorAwareTrait;

    public function __construct(
        private RouteCollector $data
    ) {
        $this->id = 'routes';
    }
}
