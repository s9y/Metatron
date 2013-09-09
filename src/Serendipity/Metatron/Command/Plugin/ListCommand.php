<?php

namespace Serendipity\Metatron\Command\Plugin;

require_once(S9Y_INCLUDE_PATH . 'include/plugin_api.inc.php');

use Serendipity\Metatron\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListCommand
 * @package Serendipity\Metatron\Command\Plugin
 */
class ListCommand extends AbstractCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('plugin:list')
            ->setDescription('Lists installed plugins.')
            ->addArgument(
                'type',
                InputArgument::OPTIONAL,
                'Types of plugins. Possible values are: event, sidebar, all (default: all).'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        $rows = $this->getPluginRows($type);
        /**@var \Symfony\Component\Console\Helper\TableHelper $table*/
        $table = $this->getApplication()->getHelperSet()->get('table');
        $table
            ->setHeaders(array('Name', 'Class', 'Type', 'Active'))
            ->setRows($rows);
        $table->render($output);
    }

    /**
     * @param $type
     * @return array
     */
    private function getPluginRows($type)
    {
        $rows = array();
        switch ($type) {
            case 'event':
                $eventPlugins = \serendipity_plugin_api::enum_plugins('event');
                $hiddenPlugins = \serendipity_plugin_api::enum_plugins('eventh');
                $plugins = array_merge((array)$eventPlugins, (array)$hiddenPlugins);
                break;
            case 'sidebar':
                $leftPlugins = \serendipity_plugin_api::enum_plugins('left');
                $rightPlugins = \serendipity_plugin_api::enum_plugins('right');
                $hiddenPlugins = \serendipity_plugin_api::enum_plugins('hide');
                $plugins = array_merge((array)$leftPlugins, (array)$rightPlugins, (array)$hiddenPlugins);
                break;
            case 'all':
            default:
                $plugins = (array)\serendipity_plugin_api::enum_plugins();
                break;
        }
        foreach ($plugins as $plugin_data) {
            $s9yPlugin = \serendipity_plugin_api::load_plugin($plugin_data['name'], $plugin_data['authorid']);
            if (!$s9yPlugin instanceof \serendipity_plugin) {
                continue;
            }
            $rows[] = array(
                $s9yPlugin->title,
                get_class($s9yPlugin),
                $s9yPlugin instanceof \serendipity_event ? 'event' : 'sidebar',
                in_array($plugin_data['placement'], array('eventh', 'hide')) ? 'hidden' : 'active'
            );
        }
        return $rows;
    }
}
