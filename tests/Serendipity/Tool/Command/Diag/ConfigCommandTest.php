<?php

require_once(S9Y_INCLUDE_PATH . 'metatron/tests/AbstractTest.php');

use Serendipity\Metatron\Application;
use Serendipity\Metatron\Command\Diag\ConfigCommand;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class ConfigCommandTest
 */
class ConfigCommandTest extends AbstractTest
{
    /**
     * @covers ConfigCommand::execute
     */
    public function testExecute()
    {
        $application = new Application();
        $application->setConfig('blogTitle', 'foobar');
        $application->add(new ConfigCommand());

        $command = $application->find('diag:config');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'name' => 'blogTitle'));

        $this->assertRegExp('/foobar/', $commandTester->getDisplay());
    }
}
