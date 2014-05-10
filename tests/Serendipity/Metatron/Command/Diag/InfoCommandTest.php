<?php

namespace Serendipity\Metatron\Command\Diag;

use Serendipity\Metatron\Application;
use Serendipity\Metatron\Command\PHPUnit\TestCase;
use Serendipity\Metatron\Model\Config;
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
        $config = new Config(S9Y_INCLUDE_PATH . 'Metatron/tests/Resources/config.yml');
        $application = new Application($config);
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
