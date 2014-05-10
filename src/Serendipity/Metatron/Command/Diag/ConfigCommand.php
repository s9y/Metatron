<?php

namespace Serendipity\Metatron\Command\Diag;

use Serendipity\Metatron\Command\CommonCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConfigCommand
 * @package Serendipity\Metatron\Command\Diag
 */
class ConfigCommand extends CommonCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('diag:config')
            ->setDescription('Prints the value of a config key.')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Name of the config key'
            )
            ->addOption(
                'search',
                's',
                InputOption::VALUE_NONE,
                'Search for substring, e.g. "blog" will find keys "blogTitle" and "blogDescription".'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $search = $input->getOption('search');
        $rows = array();
        if ($name) {
            if ($search) {
                $s9y = $this->getApplication()->getSerendipity();
                foreach ($s9y as $key => $value) {
                    if (is_scalar($value) && strpos($key, $name) !== false) {
                        $rows[] = array($key, $value);
                    }
                }
            } else {
                $rows[] = array($name, $this->getApplication()->getConfig($name));
            }
        } else {
            $s9y = $this->getApplication()->getSerendipity();
            foreach ($s9y as $key => $value) {
                if (is_scalar($value)) {
                    unset($s9y[$key]);
                    $rows[] = array($key, $value);
                }
            }
        }
        /**@var \Symfony\Component\Console\Helper\TableHelper $table*/
        $table = $this->getApplication()->getHelperSet()->get('table');
        $table
            ->setHeaders(array('Key', 'Value'))
            ->setRows($rows);
        $table->render($output);
    }
}
