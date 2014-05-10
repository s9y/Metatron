<?php

namespace Serendipity\Metatron\Command\Config;

use Serendipity\Metatron\Application;
use Serendipity\Metatron\Command\PHPUnit\TestCase;
use Serendipity\Metatron\Model\Config;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class SetCommandTest
 */
class SetCommandTest extends TestCase
{
    /**
     * @var string
     */
    protected $configFileCopy;

    /**
     * @var string
     */
    protected $configDir;

    /**
     * Set up
     */
    public function setUp()
    {
        parent::setUp();
        // Since we are manipulating the config file, create a copy of it to use here
        $this->copyConfig();
        $this->configDir = 'Metatron/tests/data/configdir';
        mkdir(S9Y_INCLUDE_PATH . $this->configDir);
    }

    /**
     * Tear down
     */
    public function tearDown()
    {
        if (is_dir(S9Y_INCLUDE_PATH . $this->configDir)) {
            rmdir(S9Y_INCLUDE_PATH . $this->configDir);
        }
        $this->removeConfigCopy();
        parent::tearDown();
    }

    /**
     * @covers SetCommand::execute
     * @dataProvider dataProvider
     */
    public function testExecuteWithInvalidKey($key, $value, $msg)
    {
        $config = new Config($this->configFileCopy);
        $application = new Application($config);
        $application->add(new SetCommand());

        $command = $application->find('config:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                'command' => $command->getName(),
                'key' => $key,
                'value' => $value
            )
        );

        $this->assertRegExp($msg, $commandTester->getDisplay());
        if ($msg == '/Saved new configuration./') {
            $configFileContents = file_get_contents($this->configFileCopy);
            $this->assertContains('backupdir', $configFileContents);
            $this->assertContains(S9Y_INCLUDE_PATH . 'Metatron/tests/data/configdir', $configFileContents);
        }
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return array(
            array('foo', 'bar', '/Option \'foo\' is not allowed./'),
            array('backupdir', 'bar', '/Directory bar does not exist or is not writable./'),
            array('backupdir', S9Y_INCLUDE_PATH . 'Metatron/tests/data/configdir', '/Saved new configuration./'),
        );
    }

    /**
     * @return void
     */
    protected function copyConfig()
    {
        $this->configFileCopy = S9Y_INCLUDE_PATH . 'Metatron/tests/data/config.yml';
        copy(S9Y_INCLUDE_PATH . 'Metatron/tests/Resources/config.yml', $this->configFileCopy);
    }

    /**
     * @return void
     */
    protected function removeConfigCopy()
    {
        if (file_exists($this->configFileCopy)) {
            unlink($this->configFileCopy);
        }
    }
}
