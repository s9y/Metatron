<?php

namespace Serendipity\Metatron\Command\Diag;

use Serendipity\Metatron\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InfoCommand
 * @package Serendipity\Metatron\Command\Diag
 */
class InfoCommand extends AbstractCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('diag:info')
            ->setDescription('Prints basic information about the current S9y installation.')
            ->addOption(
                'lang',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Output language'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lang = $input->getOption('lang') ? $input->getOption('lang') : 'en';
        $this->includeLanguageFile($lang);
        $rows = array();
        $rows[] = array('Installed S9y Version', $this->getApplication()->getConfig('versionInstalled'));
        $rows[] = array(INSTALL_BLOGNAME_DESC, $this->getApplication()->getConfig('blogTitle'));
        $rows[] = array('Active template', $this->getApplication()->getConfig('template'));
        /**@var \Symfony\Component\Console\Helper\TableHelper $table*/
        $table = $this->getApplication()->getHelperSet()->get('table');
        $table
            ->setHeaders(array('Key', 'Value'))
            ->setRows($rows);
        $table->render($output);
    }
}
