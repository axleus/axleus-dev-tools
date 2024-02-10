<?php

declare(strict_types=1);

namespace Axleus\DevTools\Console\Command;

use Laminas\Cli\Input\ParamAwareInputInterface;
use Laminas\Cli\Input\StringParam;
use Laminas\Config\Config;
use Laminas\Config\Factory as ConfigFactory;
use Laminas\Config\Writer\PhpArray as Writer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function sprintf;

final class DbConfigCommand extends AbstractCommand
{
    protected const TARGET_FILE = __DIR__ . '/../../../../../config/autoload/db.%s.php';
    /** @var string $development */
    protected static $development = 'local';
    /** @var string $dsn */
    protected static $dsn = 'mysql:dbname=%s;host=%s;port=%scharset=utf8';
    /** @var string $production */
    protected static $production = 'global';
    /** @var string $defaultName */
    protected static $defaultName = 'db-config';
    /** @var string $defaultDescription */
    protected static $defaultDescription = 'Configure MySQL connection for the Axleus Platform';
    /** @var string $defaultHelp */
    protected static $defaultHelp = 'This command allows you to configure a MySQL connection configuration for the Axleus Platform.';
    protected function configure(): void
    {
        $this->setName(self::$defaultName);
        $this->addParam(
            (new StringParam('dbname'))->setDescription('Database name')
        );
        $this->addParam(
            (new StringParam('host'))->setDescription('Hostname for the MySQL server')->setDefault('localhost')
        );
        $this->addParam(
            (new StringParam('port'))->setDescription('Port for the MySQL server')->setDefault('3306')
        );
        $this->addParam(
            (new StringParam('username'))->setDescription('Username for the MySQL server')
        );
        $this->addParam(
            (new StringParam('password'))->setDescription('Password for the MySQL server')
        );
        $this->addParam(
            (new StringParam('mode'))->setDescription('development|dev or production mode')
        );
    }

    /** @param ParamAwareInputInterface $input */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mode = $input->getParam('mode');
        // need to verify that file exist, if not... fix it
        $config = new Config(
            ConfigFactory::fromFile(
                sprintf(
                    self::TARGET_FILE,
                    $mode === 'development' || $mode === 'dev' ? self::$development : self::$production
                ),
            ),
            true
        );
        if (isset($config->db)) {
            if (isset($config->db->dsn)) {
                $config->db->dsn = sprintf(
                    self::$dsn,
                    $input->getParam('dbname'),
                    $input->getParam('host'),
                    $input->getParam('port')
                );
            }
            $config->db->username = $input->getParam('username');
            $config->db->password = $input->getParam('password');
            // let them know whats being done
            $output->writeln(
                '<info>Writing configuration data to '
                . sprintf(
                    self::TARGET_FILE,
                    $mode === 'development' || $mode === 'dev' ? self::$development : self::$production
                ) . '</info>'
            );
            // create a writer so we can write the file
            $writer = new Writer();
            $writer->setUseBracketArraySyntax(true);
            $writer->toFile(
                sprintf(
                    self::TARGET_FILE,
                    $mode === 'development' || $mode === 'dev' ? self::$development : self::$production
                ),
                $config
            );
            $output->writeln('<info>Configuration data successfully written.</info>');
        }
        return 0;
    }
}
