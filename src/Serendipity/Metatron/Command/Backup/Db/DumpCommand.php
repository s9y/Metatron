<?php

namespace Serendipity\Metatron\Command\Backup\Db;

use Serendipity\Metatron\Command\CommonCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DumpCommand
 * @package Serendipity\Metatron\Command\Backup\Db
 */
class DumpCommand extends CommonCommand
{
    const NAME = 'backup:db:dump';
    const TYPE_FULL = 'full';
    const TYPE_SCHEMA = 'schema';
    const OPTION_TYPE = 'type';
    const OPTION_GZIPPED = 'gzipped';
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Uses mysqldump to dump the database to the backup directory.')
            ->addOption(
                self::OPTION_TYPE,
                '',
                InputOption::VALUE_OPTIONAL,
                'Backup: "' . self::TYPE_SCHEMA . '", or "' . self::TYPE_FULL . '": both schema and data.',
                self::TYPE_FULL
            )
            ->addOption(
                self::OPTION_GZIPPED,
                '',
                InputOption::VALUE_NONE,
                'Compress database dump as .gz.'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->config = $this->getApplication()->getMetatronConfig();
        $backupDirectory = $this->config->get('backupdir');
        if (empty($backupDirectory)) {
            $output->writeln('<error>Backup directory not configured. Use command "config:set backupdir path_to_dir" to fix this.</error>');
            return;
        }
        try {
            $this->isShellCommandInstalled('mysqldump');
            if (((boolean)$input->getOption(self::OPTION_GZIPPED)) === true) {
                $this->isShellCommandInstalled('gzip');
            }
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            return;
        }
        $s9y = $this->getApplication()->getSerendipity();
        $destinationFilename = $backupDirectory . DIRECTORY_SEPARATOR
            . $s9y['dbName'] . '_'
            . $input->getOption(self::OPTION_TYPE) .  '_'
            . date('Ymd-His') . '.sql';
        $gzip = '';
        if (((boolean)$input->getOption(self::OPTION_GZIPPED)) === true) {
            $gzip = '| gzip -9 -c ';
            $destinationFilename .= '.gz';
        }
        $options = '';
        if ($input->getOption(self::OPTION_TYPE) === self::TYPE_SCHEMA) {
            $options .= ' --no-data';
        }
        $mysqlDump = sprintf(
            'mysqldump %s -u%s -p%s %s %s> %s',
            $options,
            $s9y['dbUser'],
            $s9y['dbPass'],
            $s9y['dbName'],
            $gzip,
            $destinationFilename
        );
        shell_exec($mysqlDump);
        $output->writeln('Wrote backup to ' . $destinationFilename);
    }

    /**
     * @param string $command
     * @throws \Exception
     * @return bool
     */
    protected function isShellCommandInstalled($command)
    {
        $result = shell_exec('which ' . $command);
        if (empty($result)) {
            throw new \Exception('Command ' . $command . ' is not installed.');
        }
        return true;
    }
}
