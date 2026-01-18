<?php

declare(strict_types=1);

/**
 * This file is part of the Webware Traccio package.
 *
 * Copyright (c) 2026 Joey Smith <jsmith@webinertia.net>
 * and contributors.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webware\Traccio\Debug;

use Mezzio\Router\RouteCollector;
use Tracy\IBarPanel;

final class RoutesPanel implements IBarPanel
{
    use IBarPanelTrait;

    public function __construct(
        private RouteCollector $data,
    ) {
        $this->id = 'routes';
    }
}
