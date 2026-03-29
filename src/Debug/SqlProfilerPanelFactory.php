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

use PhpDb\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final readonly class SqlProfilerPanelFactory
{
    public function __invoke(ContainerInterface $container): SqlProfilerPanel
    {
        /** @var AdapterInterface $adapter */
        $adapter = $container->get(AdapterInterface::class);

        return new SqlProfilerPanel($adapter);
    }
}
