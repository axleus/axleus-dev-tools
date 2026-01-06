<?php

declare(strict_types=1);

/*
 * This file is part of the Webware Traccio component.
 *
 * Copyright (c) 2023-2026 Joey Smith <jsmith@webinertia.net>
 * and contributors.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webware\Traccio\Debug;

use PhpDb\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class SqlProfilerPanelFactory
{
    public function __invoke(ContainerInterface $container): SqlProfilerPanel
    {
        return new SqlProfilerPanel($container->get(AdapterInterface::class));
    }
}
