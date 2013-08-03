<?php

namespace Serendipity\Metatron\Command\Cache;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class FlushCommand
 * @package Serendipity\Metatron\Command\Cache
 */
class FlushCommand extends Command
{
    /**
     * @const string
     */
    const DEFAULT_CACHE_DIR = 'templates_c';

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('cache:flush')
            ->addArgument(
                'dir',
                InputArgument::OPTIONAL,
                'Cache directory (Default: ' . self::DEFAULT_CACHE_DIR . ')'
            )
            ->setDescription('Flushes cache directory.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cacheDir = $this->getCacheDir($input->getArgument('dir'));
        if (!$cacheDir) {
            $output->writeln('The directory ' . $input->getArgument('dir') . ' is not a cache directory.');
            return;
        }

        $finder = new Finder();
        $iterator = $finder->directories()->in($cacheDir);
        $this->getFilesystem()->remove($iterator);

        $finder = new Finder();
        $iterator = $finder->files()->in($cacheDir);
        foreach ($iterator as $file) {
            /**@var SplFileInfo $file*/
            try {
                $this->getFilesystem()->remove($file);
            } catch (IOException $e) {
                $output->writeln($e->getMessage());
            }
        }
        $output->writeln('Successfully flushed cache directory "' . $cacheDir . '".');
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        if ($this->filesystem === null) {
            $this->filesystem = new Filesystem();
        }
        return $this->filesystem;
    }

    /**
     * @param null $dir
     * @return bool|string
     */
    protected function getCacheDir($dir = null)
    {
        if (!empty($dir)) {
            if (in_array($dir, array('archives', 'bundled-libs', 'deployment', 'docs', 'htmlarea', 'include', 'lang', 'plugins', 'sql', 'templates', 'tests', 'uploads'))) {
                return false;
            }
            return S9Y_INCLUDE_PATH . $dir;
        } else {
            return S9Y_INCLUDE_PATH . self::DEFAULT_CACHE_DIR;
        }
    }
}
