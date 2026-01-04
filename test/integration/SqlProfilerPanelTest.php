<?php

declare(strict_types=1);

namespace WebwareIntegrationTest\Traccio;

use Laminas\ServiceManager\ServiceManager;
use PhpDb\Adapter\Adapter;
use PhpDb\Adapter\AdapterInterface;
use PhpDb\Adapter\Driver\Pdo\Result;
use PhpDb\Adapter\Driver\Pdo\Statement;
use PhpDb\Adapter\Profiler\Profiler;
use PhpDb\Adapter\Sqlite\Driver\Pdo\Connection;
use PhpDb\Adapter\Sqlite\Driver\Pdo\Feature\SqliteRowCounter;
use PhpDb\Adapter\Sqlite\Driver\Pdo\Pdo;
use PhpDb\Adapter\Sqlite\Platform\Sqlite as SqlitePlatform;
use PhpDb\Sql\Ddl\Column\Integer;
use PhpDb\Sql\Ddl\Column\Varchar;
use PhpDb\Sql\Ddl\CreateTable;
use PhpDb\Sql\Sql;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tracy\IBarPanel;
use Webware\Traccio\Debug\SqlProfilerPanel;
use Webware\Traccio\Debug\SqlProfilerPanelFactory;
use Webware\Traccio\PhpDb\ProfilingDelegator;

use function count;
use function str_contains;

#[CoversClass(SqlProfilerPanel::class)]
#[CoversClass(SqlProfilerPanelFactory::class)]
#[CoversClass(ProfilingDelegator::class)]
final class SqlProfilerPanelTest extends TestCase
{
    /**
     * @var Adapter&\PhpDb\Adapter\Profiler\ProfilerAwareInterface
     */
    private Adapter $adapter;
    private SqlProfilerPanel $panel;

    protected function setUp(): void
    {
        // Create an in-memory SQLite adapter
        $this->adapter = $this->createAdapter();

        // Create the panel
        $this->panel = new SqlProfilerPanel($this->adapter);

        // Setup test database schema
        $this->createTestSchema();
    }

    private function createAdapter(): Adapter
    {
        // Using file::memory: creates a shareable in-memory database
        // Plain :memory: creates a private database per connection
        $connection = new Connection([
            'dsn' => 'sqlite::memory:',
        ]);

        // Force connection now to ensure the database persists
        $connection->connect();

        $statement = new Statement();
        $result = new Result();

        $driver = new Pdo($connection, $statement, $result, [new SqliteRowCounter()]);
        $platform = new SqlitePlatform($driver);

        $adapter = new Adapter($driver, $platform);
        $adapter->setProfiler(new Profiler());

        return $adapter;
    }

    private function createTestSchema(): void
    {
        // Use DDL to create the users table
        // In SQLite, INTEGER PRIMARY KEY is automatically auto-incrementing
        $createTable = new CreateTable('users');

        $createTable->addColumn(new Integer('id', true, null, ['autoincrement' => true]))
            ->addColumn(new Varchar('username', 50, false))
            ->addColumn(new Varchar('email', 100, false));

        $sql = new Sql($this->adapter);
        $sqlString = $sql->buildSqlString($createTable);

        // Execute the CREATE TABLE statement using EXECUTE mode
        $this->adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);
    }

    public function testPanelImplementsIBarPanel(): void
    {
        $this->assertInstanceOf(IBarPanel::class, $this->panel);
    }

    public function testPanelHasCorrectId(): void
    {
        $reflection = new \ReflectionClass($this->panel);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $this->assertSame('database', $property->getValue($this->panel));
    }

    public function testGetTabReturnsValidHtml(): void
    {
        $tab = $this->panel->getTab();

        $this->assertIsString($tab);
        $this->assertStringContainsString('<svg', $tab);
        $this->assertStringContainsString('tracy-label', $tab);
    }

    public function testGetPanelReturnsValidHtml(): void
    {
        $panel = $this->panel->getPanel();

        $this->assertIsString($panel);
        $this->assertStringContainsString('Query Profiles', $panel);
        $this->assertStringContainsString('tracy-inner', $panel);
        $this->assertStringContainsString('tracy-QueryPanel', $panel);
    }

    public function testPanelCapturesQueryProfiles(): void
    {
        // Execute a test query
        $this->adapter->query('SELECT * FROM users', Adapter::QUERY_MODE_EXECUTE);

        $profiles = $this->adapter->getProfiler()->getProfiles();

        // Should have at least 1 profile (the SELECT), possibly 2 if CREATE TABLE was profiled
        $this->assertGreaterThanOrEqual(1, count($profiles));

        // Find the SELECT query in the profiles
        $foundSelect = false;
        foreach ($profiles as $profile) {
            if (str_contains($profile['sql'], 'SELECT * FROM users')) {
                $foundSelect = true;
                break;
            }
        }
        $this->assertTrue($foundSelect, 'SELECT query was not captured');
    }

    public function testPanelDisplaysMultipleQueries(): void
    {
        // Execute multiple queries
        $this->adapter->query('INSERT INTO users (username, email) VALUES (?, ?)', ['john_doe', 'john@example.com']);
        $this->adapter->query('INSERT INTO users (username, email) VALUES (?, ?)', ['jane_doe', 'jane@example.com']);
        $this->adapter->query('SELECT * FROM users WHERE username = ?', ['john_doe']);
        $this->adapter->query('UPDATE users SET email = ? WHERE username = ?', ['newemail@example.com', 'john_doe']);

        $profiles = $this->adapter->getProfiler()->getProfiles();
        // Should have 5 profiles: 1 CREATE TABLE + 2 INSERT + 1 SELECT + 1 UPDATE
        $this->assertCount(5, $profiles);

        // Verify panel contains all queries
        $panelContent = $this->panel->getPanel();

        $this->assertStringContainsString('INSERT INTO users', $panelContent);
        $this->assertStringContainsString('SELECT * FROM users', $panelContent);
        $this->assertStringContainsString('UPDATE users', $panelContent);
    }

    public function testPanelShowsTimingInformation(): void
    {
        $this->adapter->query('SELECT * FROM users');

        $panelContent = $this->panel->getPanel();

        $this->assertStringContainsString('Timing:', $panelContent);
        $this->assertStringContainsString('Start:', $panelContent);
        $this->assertStringContainsString('End:', $panelContent);
        $this->assertStringContainsString('Elapsed:', $panelContent);
    }

    public function testPanelShowsQueryParameters(): void
    {
        // Execute query with parameters
        $this->adapter->query('INSERT INTO users (username, email) VALUES (?, ?)', ['test_user', 'test@example.com']);

        $panelContent = $this->panel->getPanel();

        $this->assertStringContainsString('Parameters:', $panelContent);
        $this->assertStringContainsString('test_user', $panelContent);
        $this->assertStringContainsString('test@example.com', $panelContent);
    }

    public function testPanelFactoryCreatesPanel(): void
    {
        $container = new ServiceManager([
            'services' => [
                AdapterInterface::class => $this->adapter,
            ],
        ]);

        $factory = new SqlProfilerPanelFactory();
        $panel = $factory($container);

        $this->assertInstanceOf(SqlProfilerPanel::class, $panel);
    }

    public function testProfilingDelegatorWrapsAdapter(): void
    {
        $container = new ServiceManager();

        // Original factory that creates a basic adapter
        $originalFactory = fn () => $this->createAdapter();

        // Apply the profiling delegator
        $delegator = new ProfilingDelegator();
        $profiledAdapter = $delegator($container, AdapterInterface::class, $originalFactory);

        $this->assertInstanceOf(AdapterInterface::class, $profiledAdapter);
        $this->assertInstanceOf(Profiler::class, $profiledAdapter->getProfiler());
    }

    public function testProfiledAdapterCapturesQueries(): void
    {
        $container = new ServiceManager();

        $originalFactory = function () {
            $adapter = $this->createAdapter();

            // Setup schema using DDL
            $createTable = new CreateTable('test');
            $createTable->addColumn(new Integer('id', true, null, ['autoincrement' => true]))
                ->addColumn(new Varchar('name', 255, false));

            $sql = new Sql($adapter);
            $sqlString = $sql->buildSqlString($createTable);
            $adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);

            return $adapter;
        };

        $delegator = new ProfilingDelegator();
        /** @var Adapter $profiledAdapter */
        $profiledAdapter = $delegator($container, AdapterInterface::class, $originalFactory);

        // Execute test queries
        $profiledAdapter->query('INSERT INTO test (name) VALUES (?)', ['test1']);
        $profiledAdapter->query('SELECT * FROM test', Adapter::QUERY_MODE_EXECUTE);

        $profiles = $profiledAdapter->getProfiler()->getProfiles();

        // Should have 3 profiles (CREATE, INSERT, SELECT)
        $this->assertGreaterThanOrEqual(2, count($profiles));

        // Verify queries were captured
        $foundInsert = false;
        $foundSelect = false;

        foreach ($profiles as $profile) {
            if (str_contains($profile['sql'], 'INSERT INTO test')) {
                $foundInsert = true;
            }

            if (str_contains($profile['sql'], 'SELECT * FROM test')) {
                $foundSelect = true;
            }
        }

        $this->assertTrue($foundInsert, 'INSERT query was not captured');
        $this->assertTrue($foundSelect, 'SELECT query was not captured');
    }

    public function testTabShowsQueryCount(): void
    {
        // Execute multiple queries
        $this->adapter->query('SELECT * FROM users');
        $this->adapter->query('SELECT COUNT(*) FROM users');

        $tabContent = $this->panel->getTab();

        // The tab should contain the query count
        $this->assertStringContainsString('2', $tabContent);
    }

    public function testSetDataUpdatesAdapterReference(): void
    {
        // Create a second adapter with different queries
        $newAdapter = $this->createAdapter();

        // Setup schema using DDL
        $createTable = new CreateTable('test2');
        $createTable->addColumn(new Integer('id'));

        $sql = new Sql($newAdapter);
        $sqlString = $sql->buildSqlString($createTable);
        $newAdapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);

        $newAdapter->query('SELECT * FROM test2');

        // Update the panel's data
        $this->panel->setData($newAdapter);

        $panelContent = $this->panel->getPanel();
        $this->assertStringContainsString('test2', $panelContent);
    }

    public function testPanelHandlesEmptyProfiles(): void
    {
        // Create a fresh adapter with no queries
        $emptyAdapter = $this->createAdapter();

        $panel = new SqlProfilerPanel($emptyAdapter);

        $panelContent = $panel->getPanel();

        // Panel should render without errors even with no profiles
        $this->assertIsString($panelContent);
        $this->assertStringContainsString('Query Profiles', $panelContent);
    }

    public function testComplexQueryWithMultipleParameters(): void
    {
        $this->adapter->query(
            'INSERT INTO users (username, email) VALUES (?, ?), (?, ?), (?, ?)',
            ['user1', 'user1@example.com', 'user2', 'user2@example.com', 'user3', 'user3@example.com']
        );

        $profiles = $this->adapter->getProfiler()->getProfiles();
        $lastProfile = $profiles[count($profiles) - 1];

        $this->assertStringContainsString('INSERT INTO users', $lastProfile['sql']);
        $this->assertCount(6, $lastProfile['parameters']->getNamedArray());

        $panelContent = $this->panel->getPanel();

        $this->assertStringContainsString('user1@example.com', $panelContent);
        $this->assertStringContainsString('user2@example.com', $panelContent);
        $this->assertStringContainsString('user3@example.com', $panelContent);
    }
}
