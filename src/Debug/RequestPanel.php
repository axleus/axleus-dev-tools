<?php

declare(strict_types=1);

namespace Webware\Traccio\Debug;

use Psr\Http\Message\ServerRequestInterface;
use Tracy\IBarPanel;

final class RequestPanel implements IBarPanel
{
    use IBarPanelTrait;

    public function __construct(
        private ?ServerRequestInterface $data = null,
    ) {
        $this->id = 'request';
    }
}
