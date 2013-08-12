<?php

require_once(S9Y_INCLUDE_PATH . 'metatron/tests/AbstractTest.php');

use Serendipity\Metatron\Application;
use Serendipity\Metatron\Command\Diag\VersionCommand;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class VersionCommandTest
 */
class VersionCommandTest extends AbstractTest
{
    /**
     * @covers VersionCommand::execute
     */
    public function testExecute()
    {
        $application = new Application();
        $application->setConfig('versionInstalled', '2.0-alpha1');
        $application->add(new VersionCommand());

        $command = $application->find('diag:version');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('/2.0-alpha1/', $commandTester->getDisplay());
    }
}
