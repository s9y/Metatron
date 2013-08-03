<?php

namespace Serendipity\Metatron\Command\Diag;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class VersionCommand
 * @package Serendipity\Metatron\Command\Diag
 */
class VersionCommand extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('diag:version')
            ->setDescription('Prints the current S9y version');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $version = $this->getApplication()->getConfig('versionInstalled');
        $output->writeln($version);
    }
}
