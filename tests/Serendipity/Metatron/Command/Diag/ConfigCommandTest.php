<?php

namespace Serendipity\Metatron\Command\Diag;

use Serendipity\Metatron\Application;
use Serendipity\Metatron\Command\PHPUnit\TestCase;
use Serendipity\Metatron\Model\Config;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class ConfigCommandTest
 */
class ConfigCommandTest extends TestCase
{
    /**
     * @covers ConfigCommand::execute
     */
    public function testExecute()
    {
        $config = new Config(S9Y_INCLUDE_PATH . 'Metatron/tests/Resources/config.yml');
        $application = new Application($config);
        $application->setConfig('blogTitle', 'foobar');
        $application->add(new ConfigCommand());

        $command = $application->find('diag:config');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'name' => 'blogTitle'));

        $this->assertRegExp('/foobar/', $commandTester->getDisplay());
    }
}
