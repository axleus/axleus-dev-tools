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

namespace Webware\Traccio;

use Psr\Container\ContainerInterface;
use Tracy\Debugger;

/**
 * @internal
 */
final readonly class Configuration
{
    public const LOG_DIRECTORY = __DIR__ . '/../../../../data/tracy';

    /** @var array{debug?: bool, Debugger::class?: TracyConfig} */
    private static array $config;

    /**
     * @return TracyConfig
     */
    public static function get(ContainerInterface $container): array
    {
        if (! isset(self::$config)) {
            self::$config = $container->has('config')
                ? $container->get('config')
                : [];
        }

        return self::$config[Debugger::class] ?? [];
    }

    public static function debug(ContainerInterface $container): bool
    {
        self::get($container);

        return self::$config['debug'] ?? false;
    }

    public static function enable(ContainerInterface $container): bool
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['enable'] ?? false;
    }

    public static function productionMode(ContainerInterface $container): ?bool
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['productionMode'] ?? null;
    }

    public static function showBar(ContainerInterface $container): bool
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['showBar'] ?? false;
    }

    public static function reservedMemorySize(ContainerInterface $container): int
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['reservedMemorySize'] ?? Debugger::$reservedMemorySize;
    }

    public static function strictMode(ContainerInterface $container): bool|int
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['strictMode'] ?? Debugger::$strictMode;
    }

    public static function scream(ContainerInterface $container): bool|int
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['scream'] ?? Debugger::$scream;
    }

    public static function maxDepth(ContainerInterface $container): int
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['maxDepth'] ?? Debugger::$maxDepth;
    }

    public static function maxLength(ContainerInterface $container): int
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['maxLength'] ?? Debugger::$maxLength;
    }

    public static function maxItems(ContainerInterface $container): int
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['maxItems'] ?? Debugger::$maxItems;
    }

    public static function showLocation(ContainerInterface $container): ?bool
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['showLocation'] ?? Debugger::$showLocation;
    }

    public static function keysToHide(ContainerInterface $container): array
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['keysToHide'] ?? Debugger::$keysToHide;
    }

    public static function dumpTheme(ContainerInterface $container): string
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['dumpTheme'] ?? Debugger::$dumpTheme;
    }

    public static function logDirectory(ContainerInterface $container): ?string
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['logDirectory'] ?? self::LOG_DIRECTORY;
    }

    public static function logSeverity(ContainerInterface $container): int
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['logSeverity'] ?? Debugger::$logSeverity;
    }

    public static function email(ContainerInterface $container): string|array|null
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['email'] ?? Debugger::$email;
    }

    public static function editor(ContainerInterface $container): ?string
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['editor'] ?? Debugger::$editor;
    }

    public static function editorMapping(ContainerInterface $container): array
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['editorMapping'] ?? Debugger::$editorMapping;
    }

    public static function browser(ContainerInterface $container): ?string
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['browser'] ?? Debugger::$browser;
    }

    public static function errorTemplate(ContainerInterface $container): ?string
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['errorTemplate'] ?? Debugger::$errorTemplate;
    }

    public static function customCssFiles(ContainerInterface $container): array
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['customCssFiles'] ?? Debugger::$customCssFiles;
    }

    public static function customJsFiles(ContainerInterface $container): array
    {
        $tracyConfig = self::get($container);

        return $tracyConfig['customJsFiles'] ?? Debugger::$customJsFiles;
    }
}
