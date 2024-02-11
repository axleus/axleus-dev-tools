<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Laminas\I18n\Translator\TranslatorAwareTrait;
use Psr\Http\Message\ServerRequestInterface;
use Tracy\IBarPanel;

final class RequestPanel implements IBarPanel
{
    use IBarPanelTrait;
    use TranslatorAwareTrait;

    public function __construct(
        private ?ServerRequestInterface $data = null
    ) {
        $this->id = 'request';
    }
}
