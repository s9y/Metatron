<?php

namespace Serendipity\Metatron\Command\Config;

use Serendipity\Metatron\Command\CommonCommand;
use Serendipity\Metatron\Exception\InvalidKeyException;
use Serendipity\Metatron\Model\Config;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SetDirCommand
 * @package Serendipity\Metatron\Command\Backup
 */
class SetCommand extends CommonCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('config:set')
            ->setDescription('Set config options.')
            ->addArgument(
                'key',
                InputArgument::REQUIRED,
                'Name of the config key.'
            )
            ->addArgument(
                'value',
                InputArgument::REQUIRED,
                'Value of the config key.'
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Config $config */
        $config = $this->getApplication()->getMetatronConfig();
        $key = $input->getArgument('key');
        $value = $input->getArgument('value');
        try {
            $config->set($key, $value);
        } catch (InvalidKeyException $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            $output->writeln('Here is a list of valid options:');
            foreach ($config->getOptionValidators() as $name => $type) {
                $output->writeln('* ' . $name . '');
            }
            return;
        } catch (\Exception $e) {
            $output->writeln('here');
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return;
        }
        $config->save();
        $output->writeln('<info>Saved new configuration.</info>');
    }

}
