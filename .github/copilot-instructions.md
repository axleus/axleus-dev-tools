# Webware\Traccio - GitHub Copilot Instructions

## Project Overview

**Webware\Traccio** is a Tracy Debugger integration and profiler library for Mezzio PHP applications. It provides a seamless way to integrate the powerful Tracy debugging bar into Mezzio applications with custom debug panels for configuration, routes, requests, and database profiling.

### Key Information
- **License**: BSD-3-Clause
- **PHP Versions**: 8.2, 8.3, 8.4, 8.5
- **Primary Framework**: Mezzio (PHP middleware framework)
- **Core Debugger**: Tracy (tracy/tracy ^2.11.0)
- **Namespace**: `Webware\Traccio`
- **Config Provider**: `Webware\Traccio\ConfigProvider`

## Architecture

### Core Components

#### 1. Application Class (`src/Application.php`)
- **Extends**: `Mezzio\Application` (ignores final by phpstan)
- **Purpose**: Enhanced application class with Tracy timer integration
- **Key Features**:
  - Wraps `handle()` method with `Debugger::timer()` for performance tracking
  - Maintains full compatibility with Mezzio's middleware pipeline
  - Constructor injects: `MiddlewareFactoryInterface`, `MiddlewarePipeInterface`, `RouteCollectorInterface`, `RequestHandlerRunnerInterface`

#### 2. ConfigProvider (`src/ConfigProvider.php`)
- **Purpose**: Laminas component installer configuration provider
- **Provides**:
  - Service factory definitions
  - Default Tracy configuration (dark theme, key hiding for sensitive data)
  - Optional database profiling delegator (commented out by default)

### Debug Panel Architecture

All debug panels follow a consistent pattern:

#### Panel Structure
Each panel consists of three components:

1. **Panel Class** (e.g., `RoutesPanel.php`):
   - Implements `Tracy\IBarPanel` interface
   - Uses `IBarPanelTrait` for common functionality
   - Constructor receives typed data dependency
   - Has a unique `$id` property matching panel name

2. **Factory Class** (e.g., `RoutesPanelFactory.php`):
   - Resolves dependencies from PSR-11 container
   - Creates and returns panel instance with required data

3. **Template Files** (in `src/Debug/panels/`):
   - **Tab template** (`{id}.tab.phtml`): Icon/label shown in Tracy bar
   - **Panel template** (`{id}.panel.phtml`): Full panel content when clicked

#### IBarPanelTrait (`src/Debug/IBarPanelTrait.php`)
- **Purpose**: Provides common panel rendering logic
- **Methods**:
  - `getTab()`: Renders tab using `{$id}.tab.phtml` template
  - `getPanel()`: Renders panel content using `{$id}.panel.phtml` template
  - `setData($data)`: Updates panel data dynamically
- **Uses**: `Tracy\Helpers::capture()` for output buffering

### Existing Debug Panels

#### 1. ConfigPanel (`src/Debug/ConfigPanel.php`)
- **ID**: `config`
- **Data**: `array` - Application configuration
- **Purpose**: Display full application configuration in Tracy bar
- **Factory**: Retrieves config from container

#### 2. RequestPanel (`src/Debug/RequestPanel.php`)
- **ID**: `request`
- **Data**: `?ServerRequestInterface` - Current PSR-7 request
- **Purpose**: Display HTTP request details (headers, method, URI, etc.)
- **Factory**: Can accept null (set later by middleware)

#### 3. RoutesPanel (`src/Debug/RoutesPanel.php`)
- **ID**: `routes`
- **Data**: `RouteCollector` - Mezzio route collector
- **Purpose**: Display all registered application routes
- **Factory**: Injects `RouteCollectorInterface` from container

#### 4. SqlProfilerPanel (`src/Debug/SqlProfilerPanel.php`)
- **ID**: `database`
- **Data**: `AdapterInterface` (PhpDb) - Database adapter with profiler
- **Purpose**: Display SQL query profiling information
- **Factory**: Conditional - only registered when `PhpDb\Adapter\AdapterInterface` exists
- **Dependency**: Requires `php-db/phpdb` and profiling delegator

### Middleware

#### TracyDebuggerMiddleware (`src/Middleware/TracyDebuggerMiddleware.php`)
- **Purpose**: Initialize Tracy debugger and register panels
- **Constructor Parameters**:
  - `bool $debug` - Whether debug mode is enabled
  - `array $tracyConfig` - Tracy configuration (theme, keys to hide, etc.)
  - `?ConfigPanel $configPanel` - Optional config panel
  - `?SqlProfilerPanel $sqlProfilerPanel` - Optional database panel
  - `?RoutesPanel $routesPanel` - Optional routes panel
- **Process Flow**:
  1. Check if debug mode is enabled
  2. Apply Tracy configuration (set Debugger properties dynamically)
  3. Add panels to Tracy bar if available
  4. Pass request to next handler

#### TracyDebuggerMiddlewareFactory (`src/Middleware/TracyDebuggerMiddlewareFactory.php`)
- **Purpose**: Create middleware with conditional panel registration
- **Logic**:
  - Retrieves `debug` flag from config
  - Retrieves Tracy configuration from `config[Debugger::class]`
  - Conditionally resolves panels based on container availability
  - SQL profiler panel only created if PhpDb adapter class exists

#### RequestPanelMiddleware (`src/Middleware/RequestPanelMiddleware.php`)
- **Purpose**: Inject current request into RequestPanel after routing
- **Factory**: `RequestPanelMiddlewareFactory`

### Database Profiling Integration

#### ProfilingDelegator (`src/PhpDb/ProfilingDelegator.php`)
- **Purpose**: Wrap PhpDb adapter with profiler
- **Type**: Service manager delegator
- **Process**:
  1. Calls original factory to get adapter
  2. Attaches `PhpDb\Adapter\Profiler\Profiler` to adapter
  3. Returns profiler-enabled adapter
- **Activation**: Uncomment delegator configuration in `ConfigProvider::getDependencies()`

## Development Patterns

### Creating a New Debug Panel

Follow this pattern when adding new debug panels:

1. **Create Panel Class** (`src/Debug/{Name}Panel.php`):
```php
<?php
declare(strict_types=1);

namespace Webware\Traccio\Debug;

use Tracy\IBarPanel;

final class {Name}Panel implements IBarPanel
{
    use IBarPanelTrait;

    public function __construct(
        private {DataType} $data,
    ) {
        $this->id = '{panel-id}';
    }
}
```

2. **Create Factory** (`src/Debug/{Name}PanelFactory.php`):
```php
<?php
declare(strict_types=1);

namespace Webware\Traccio\Debug;

use Psr\Container\ContainerInterface;

final class {Name}PanelFactory
{
    public function __invoke(ContainerInterface $container): {Name}Panel
    {
        return new {Name}Panel(
            $container->get({DependencyInterface}::class)
        );
    }
}
```

3. **Create Templates**:
   - `src/Debug/panels/{panel-id}.tab.phtml` - SVG icon + label
   - `src/Debug/panels/{panel-id}.panel.phtml` - Full panel content

4. **Register in ConfigProvider** (`src/ConfigProvider.php`):
```php
'factories' => [
    Debug\{Name}Panel::class => Debug\{Name}PanelFactory::class,
],
```

5. **Add to Middleware** (if needed):
   - Update `TracyDebuggerMiddleware` constructor to accept panel
   - Update `TracyDebuggerMiddlewareFactory` to resolve panel
   - Add panel to Tracy bar in middleware `process()` method

### Panel Template Guidelines

#### Tab Template Pattern:
```php
<?php
declare(strict_types=1);

namespace Webware\Traccio\Debug;

if (!isset($data)) {
    return;
}
?>
<svg viewBox="0 0 640 512">
    <!-- FontAwesome or custom SVG icon -->
</svg>
<span class="tracy-label"><?=isset($title) ? $title : 'Panel Name'?></span>
```

#### Panel Template Pattern:
```php
<?php
declare(strict_types=1);

namespace Webware\Traccio\Debug;

use Tracy\Debugger;
use Tracy\Dumper;

if (!$data instanceof {ExpectedType}) {
    return;
}

Dumper::renderAssets();
Debugger::$maxLength = 250; // Adjust as needed
?>
<div class="tracy-inner tracy-{CustomClass}">
    <div class="tracy-inner-container">
        <?php
        // Use Tracy\Dumper::dump() for complex data
        // Or custom HTML for structured display
        ?>
    </div>
</div>
```

## Configuration

### Tracy Configuration (`config/autoload/tracy.global.php`):
```php
use Tracy\Debugger;

return [
    'debug' => true, // Enable debug mode
    Debugger::class => [
        'dumpTheme' => 'dark', // 'light' or 'dark'
        'keysToHide' => [
            'password',
            'pass',
            'secret',
            'token',
            'api_key',
        ],
        // Additional Tracy options
        'maxDepth' => 10,
        'maxLength' => 250,
        'showLocation' => true,
    ],
];
```

### Enabling Database Profiling:
In your application's `ConfigProvider` or config file:
```php
'dependencies' => [
    'delegators' => [
        \PhpDb\Adapter\AdapterInterface::class => [
            \Webware\Traccio\PhpDb\ProfilingDelegator::class,
        ],
    ],
],
```

## Testing

### Test Structure
- **Unit Tests**: `test/unit/` (namespace: `WebwareTest\Traccio`)
- **Integration Tests**: `test/integration/` (namespace: `WebwareIntegrationTest\Traccio`)
- **Test Assets**: `test/asset/` (namespace: `WebwareTestAsset\Traccio`)

### Running Tests
```bash
composer test              # Unit tests only
composer test-integration  # Integration tests
composer check            # Full check (cs-check, static analysis, all tests)
```

### Code Quality
```bash
composer cs-check         # Check code style
composer cs-fix          # Fix code style
composer sa              # Static analysis with PHPStan
composer sa-verbose      # Verbose static analysis
```

## Dependencies

### Required
- `php`: ~8.2.0 || ~8.3.0 || ~8.4.0 || ~8.5.0
- `tracy/tracy`: ^2.11.0

### Development
- `mezzio/mezzio`: ^3.26
- `laminas/laminas-diactoros`: ^3.8 (PSR-7 implementation)
- `phpunit/phpunit`: ^11.5
- `phpstan/phpstan`: ^2.1
- `friendsofphp/php-cs-fixer`: ^3.92

### Optional
- `php-db/phpdb-adapter-sqlite`: ^0.2.0 (for database profiling demo)

## Coding Standards

### PSR Compliance
- **PSR-4**: Autoloading standard
- **PSR-7**: HTTP message interfaces
- **PSR-11**: Container interface
- **PSR-15**: HTTP server middleware

### Code Style
- **Strict Types**: All files use `declare(strict_types=1);`
- **Final Classes**: Use `final` keyword for classes that shouldn't be extended
- **Type Hints**: Full type declarations on all properties, parameters, and returns
- **Readonly Pattern**: Constructor property promotion with `private` visibility

### PHPStan Configuration
- **Level**: Maximum strictness
- **Ignores**:
  - `class.extendsFinalByPhpDoc` for Application extending final MezzioApplication
- **Baseline**: `phpstan-baseline.neon` for known/acceptable issues

## Key Integration Points

### Mezzio Application Bootstrap
```php
// In config/pipeline.php or similar
$app->pipe(\Webware\Traccio\Middleware\TracyDebuggerMiddleware::class);
// ... other middleware
$app->pipe(\Webware\Traccio\Middleware\RequestPanelMiddleware::class);
```

### Custom Application Factory
Replace Mezzio's default application factory in `dependencies.php`:
```php
use Mezzio\Application;
use Webware\Traccio\Container\ApplicationFactory;

return [
    'dependencies' => [
        'factories' => [
            Application::class => ApplicationFactory::class,
        ],
    ],
];
```

## Extension Opportunities

### Potential New Panels
- **Session Panel**: Display session data
- **Cache Panel**: Show cache hits/misses and stored keys
- **Event Panel**: Display dispatched events
- **Authentication Panel**: Show current user and permissions
- **Performance Panel**: Display detailed timing breakdown

### Integration Points
- **Laminas Components**: Additional Laminas-specific panels
- **Doctrine**: Database query profiling for Doctrine ORM
- **Logging**: PSR-3 logger integration panel
- **Queue/Job Systems**: Background job monitoring

## Important Notes

- Always use `declare(strict_types=1);` at the top of new PHP files
- Panel IDs must match template filenames (`{id}.tab.phtml`, `{id}.panel.phtml`)
- Database profiling requires explicit delegator configuration
- Tracy debugger only activates when `config['debug'] === true`
- All panels are optional and conditionally registered
- Use `Tracy\Dumper` for rendering complex data structures
- SVG icons in tabs should use Tracy's color scheme (`#7a86b8`)

## Maintenance

- **Repository**: tyrsson/traccio
- **Branch**: 0.1.x
- **Security**: Uses roave/security-advisories for vulnerability scanning
- **Renovate**: Automated dependency updates enabled
