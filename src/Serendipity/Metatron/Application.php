<?php

namespace Serendipity\Metatron;

use Serendipity\Metatron\Command\Backup\Db\DumpCommand;
use Serendipity\Metatron\Command\Cache\FlushCommand;
use Serendipity\Metatron\Command\Config\SetCommand;
use Serendipity\Metatron\Command\Diag\ConfigCommand;
use Serendipity\Metatron\Command\Diag\InfoCommand;
use Serendipity\Metatron\Command\Diag\VersionCommand;
use Serendipity\Metatron\Command\SelfUpdateCommand;
use Serendipity\Metatron\Command\User\ListCommand as UserListCommand;
use Serendipity\Metatron\Command\User\PasswordCommand;
use Serendipity\Metatron\Command\Comment\ListCommand as CommentListCommand;
use Serendipity\Metatron\Command\Comment\ApproveCommand as CommentApproveCommand;
use Serendipity\Metatron\Command\Plugin\ListCommand as PluginListCommand;
use Serendipity\Metatron\Model\Config;
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
    const APP_VERSION = '0.1.3';

    /**
     * @var null
     */
    protected $autoloader;

    /**
     * Serendipity configuration
     *
     * @var array
     */
    protected $serendipity;

    /**
     * Metatron configuration
     *
     * @var Config
     */
    protected $metatronConfig;

    /**
     * @param Config $config
     * @param null $autoloader
     */
    public function __construct(Config $config, $autoloader = null)
    {
        $this->metatronConfig = $config;
        $this->autoloader = $autoloader;
        parent::__construct(self::APP_NAME, self::APP_VERSION);
        $this->loadConfig();
    }

    /**
     * @todo get rid of global $s9y that has been introduced for testing purposes
     * @return void
     */
    protected function loadConfig()
    {
        global $serendipity, $s9y;
        if (is_array($s9y)) {
            $this->serendipity = array_merge($serendipity, $s9y);
        } else {
            $this->serendipity = $serendipity;
        }
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
        $this->add(new DumpCommand());
        $this->add(new SetCommand());
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

    /**
     * @param \Serendipity\Metatron\Model\Config $metatronConfig
     */
    public function setMetatronConfig($metatronConfig)
    {
        $this->metatronConfig = $metatronConfig;
    }

    /**
     * @return \Serendipity\Metatron\Model\Config
     */
    public function getMetatronConfig()
    {
        return $this->metatronConfig;
    }
}
