<?php

declare(strict_types=1);

namespace Axleus\DevTools;

use Axleus\Service\Delegator\TranslatorAwareInterfaceDelegatorFactory as TranslatorDelegator;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\I18n\Translator\Loader\PhpArray;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'debug_overrides'     => [
                'show_debugger_in_production' => false, // in case we want to see timings etc in production
            ],
            'dependencies'        => $this->getDependencies(),
            'laminas-cli'         => $this->getConsoleConfig(),
            'middleware_pipeline' => $this->getPipelineConfig(),
            'translator'          => $this->getTranslatorConfig(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'factories' => [
                Console\Command\DbConfigCommand::class    => Console\Command\Factory\DbConfigCommandFactory::class,
                Console\Command\BuildDbCommand::class     => Console\Command\Factory\BuildDbCommandFactory::class,
                Debug\ConfigPanel::class                  => Debug\ConfigPanelFactory::class,
                Debug\RequestPanel::class                 => Debug\RequestPanelFactory::class,
                Debug\SqlProfilerPanel::class             => Debug\SqlProfilerPanelFactory::class,
                Debug\RoutesPanel::class                  => Debug\RoutesPanelFactory::class,
                Middleware\TracyDebuggerMiddleware::class => Middleware\TracyDebuggerMiddlewareFactory::class,
                Middleware\RequestPanelMiddleware::class  => Middleware\RequestPanelMiddlewareFactory::class,
            ],
            'delegators' => [
                AdapterInterface::class => [
                    Db\Adapter\AdapterServiceDelegatorFactory::class,
                ],
                Debug\ConfigPanel::class => [
                    TranslatorDelegator::class,
                ],
                Debug\RequestPanel::class => [
                    TranslatorDelegator::class,
                ],
                Debug\SqlProfilerPanel::class => [
                    TranslatorDelegator::class,
                ],
                Debug\RoutesPanel::class => [
                    TranslatorDelegator::class,
                ],
            ],
        ];
    }

    public function getConsoleConfig(): array
    {
        return [
            'commands' => [
                'axleus:db:write-config' => Console\Command\DbConfigCommand::class,
                'axleus:db:create'       => Console\Command\BuildDbCommand::class,
            ],
            // 'chains'   => [
            //     Console\Command\DbConfigCommand::class => [
            //         Console\Command\BuildDbCommand::class => [],
            //     ],
            // ],
        ];
    }

    public function getPipelineConfig(): array
    {
        return [
            [
                'middleware' => [
                    Middleware\TracyDebuggerMiddleware::class,
                ],
                'priority' => 12000,
            ],
            [
                'middleware' => [
                    Middleware\RequestPanelMiddleware::class,
                ],
                'priority' => 1,
            ],
        ];
    }

    public function getTranslatorConfig(): array
    {
        return [
            'translation_file_patterns' => [ // This is the only config that is needed for 1 translation per file
                [
                    'type'     => PhpArray::class,
                    'filename' => 'en_US.php',
                    'base_dir' => __DIR__ . '/../language',
                    'pattern'  => '%s.php',
                ],
            ],
            'translation_files' => [
                [
                    'type'        => PhpArray::class,
                    'filename'    => __DIR__ . '/../language/en_US.php',
                    'locale'      => 'en_US',
                    'text_domain' => 'dev.tools',
                ],
            ],
        ];
    }
}