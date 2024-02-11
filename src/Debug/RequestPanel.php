<?php

declare(strict_types=1);

namespace Axleus\DevTools\Debug;

use Psr\Http\Message\ServerRequestInterface;
use Tracy\IBarPanel;

final class RequestPanel implements IBarPanel
{
    use IBarPanelTrait;

    public function __construct(
        private ServerRequestInterface $data
    ) {
        $this->id = 'request';
    }
}
