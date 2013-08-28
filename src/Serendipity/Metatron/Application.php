<?php

namespace Serendipity\Metatron;

use Serendipity\Metatron\Command\Cache\FlushCommand;
use Serendipity\Metatron\Command\Diag\ConfigCommand;
use Serendipity\Metatron\Command\Diag\InfoCommand;
use Serendipity\Metatron\Command\Diag\VersionCommand;
use Serendipity\Metatron\Command\SelfUpdateCommand;
use Serendipity\Metatron\Command\User\ListCommand as UserListCommand;
use Serendipity\Metatron\Command\User\PasswordCommand;
use Serendipity\Metatron\Command\Comment\ListCommand as CommentListCommand;
use Serendipity\Metatron\Command\Comment\ApproveCommand as CommentApproveCommand;
use Serendipity\Metatron\Command\Plugin\ListCommand as PluginListCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Application
 * @package Serendipity\Metatron
 */
class Application extends BaseApplication
{
    /**
     * Application name
     */
    const APP_NAME = 'metatron';

    /**
     * Application version
     */
    const APP_VERSION = '0.1.2';

    /**
     * @var null
     */
    protected $autoloader;

    /**
     * @var array
     */
    protected $serendipity;

    /**
     * @param null $autoloader
     */
    public function __construct($autoloader = null)
    {
        $this->autoloader = $autoloader;
        parent::__construct(self::APP_NAME, self::APP_VERSION);
        $this->loadConfig();
    }

    /**
     * @return void
     */
    protected function loadConfig()
    {
        global $serendipity;
        $this->serendipity = $serendipity;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $this->registerCommands();
        $return = parent::run($input, $output);
        // Fix for no return values -> used in interactive shell to prevent error output
        if ($return === null) {
            return 0;
        }
        return $return;
    }

    /**
     * @return void
     */
    protected function registerCommands()
    {
        $this->add(new VersionCommand());
        $this->add(new InfoCommand());
        $this->add(new ConfigCommand());
        $this->add(new FlushCommand());
        $this->add(new UserListCommand());
        $this->add(new PasswordCommand());
        $this->add(new SelfUpdateCommand());
        $this->add(new PluginListCommand());
        $this->add(new CommentListCommand());
        $this->add(new CommentApproveCommand());
    }

    /**
     * @param $key
     * @return string|null
     */
    public function getConfig($key)
    {
        if (!isset($this->serendipity[$key])) {
            return null;
        }
        return $this->serendipity[$key];
    }

    /**
     * @return array
     */
    public function getSerendipity()
    {
        return $this->serendipity;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setConfig($key, $value)
    {
        $this->serendipity[$key] = $value;
    }
}
