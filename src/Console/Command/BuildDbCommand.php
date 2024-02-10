<?php

declare(strict_types=1);

namespace Axleus\DevTools\Console\Command;

use DirectoryIterator;
use Laminas\Cli\Input\ParamAwareInputInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use SplFileObject;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function sprintf;

class BuildDbCommand extends AbstractCommand
{
    protected const MYSQL_DIR = __DIR__ . '/../../../../../../.docker/mysql';
    /** @var Adapter $dbAdapter */
    protected $dbAdapter;

    public function __construct(
        private AdapterInterface $adapter,
        private array $config
    ) {
        parent::__construct();
        $this->dbAdapter = $adapter;
    }

    public function configure(): void
    {
    }

    /** @var ParamAwareInputInterface $input */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $dirIterator = new DirectoryIterator(self::MYSQL_DIR);
        foreach ($dirIterator as $file) {
            if ($file->isDot()) {
                continue;
            }
            $output->writeln('<info>' . sprintf('Using path %s', $file->getPathname()) . '</info>');
            $output->writeln('<info>' . sprintf('Processing %s', $file->getFilename()) . '</info>');

            $fileHandler = new SplFileObject($file->getPathname(), 'r');
            $fileHandler->setFlags(SplFileObject::DROP_NEW_LINE | SplFileObject::SKIP_EMPTY | SplFileObject::READ_AHEAD);
            $query = $fileHandler->fread($fileHandler->getSize());

            $this->dbAdapter->query($query, Adapter::QUERY_MODE_EXECUTE);
        }
        $output->writeln('<comment>Database build complete</comment>');
        return 0;
    }
}
