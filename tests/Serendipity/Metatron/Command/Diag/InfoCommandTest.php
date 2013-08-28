<?php

namespace Serendipity\Metatron\Command\Diag;

use Serendipity\Metatron\Application;
use Serendipity\Metatron\Command\PHPUnit\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class InfoCommandTest
 */
class InfoCommandTest extends TestCase
{
    /**
     * @covers InfoCommand::execute
     */
    public function testExecute()
    {
        $application = new Application();
        $application->setConfig('versionInstalled', '2.0-alpha1');
        $application->setConfig('blogTitle', 'Metatron Blog');
        $application->setConfig('template', '2k11');
        $application->add(new InfoCommand());

        $command = $application->find('diag:info');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('/2.0-alpha1/', $commandTester->getDisplay());
        $this->assertRegExp('/Metatron Blog/', $commandTester->getDisplay());
        $this->assertRegExp('/2k11/', $commandTester->getDisplay());
    }
}
