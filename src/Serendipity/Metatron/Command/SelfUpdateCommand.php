<?php

namespace Serendipity\Metatron\Command;

use Composer\IO\ConsoleIO;
use Composer\Util\RemoteFilesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SelfUpdateCommand
 * @package Serendipity\Metatron\Command
 */
class SelfUpdateCommand extends AbstractCommand
{
    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('self-update')
            ->setAliases(array('selfupdate'))
            ->setDescription('Updates Metatron to the latest version.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \RuntimeException
     * @throws \Exception
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new ConsoleIO($input, $output, $this->getHelperSet());
        $rfs = new RemoteFilesystem($io);
        $versionTxtUrl = 'https://raw.github.com/s9y/Metatron/master/version.txt';
        $remoteFilename = 'https://raw.github.com/s9y/Metatron/master/metatron.phar';

        $latest = trim($rfs->getContents('raw.github.com', $versionTxtUrl, false));

        if ($this->getApplication()->getVersion() !== $latest) {
            $output->writeln(sprintf("Updating to version <info>%s</info>.", $latest));

            $localFilename = $_SERVER['argv'][0];
            if (!is_writable($localFilename)) {
                throw new \RuntimeException('phar is not writeable. Please change permissions or run as root or with sudo.');
            }

            $tempFilename = basename($localFilename, '.phar').'-temp.phar';

            $rfs->copy('raw.github.com', $remoteFilename, $tempFilename);

            try {
                @chmod($tempFilename, 0777 & ~umask());
                // test the phar validity
                $phar = new \Phar($tempFilename);
                // free the variable to unlock the file
                unset($phar);
                @rename($tempFilename, $localFilename);
                $output->writeln('<info>Successfully updated Metatron</info>');
            } catch (\Exception $e) {
                if (!$e instanceof \UnexpectedValueException && !$e instanceof \PharException) {
                    throw $e;
                }
                unlink($tempFilename);
                $output->writeln('<error>The download is corrupted ('.$e->getMessage().').</error>');
                $output->writeln('<error>Please re-run the self-update command to try again.</error>');
            }
        } else {
            $output->writeln("<info>You are using the latest version of Metatron.</info>");
        }
    }
} 
