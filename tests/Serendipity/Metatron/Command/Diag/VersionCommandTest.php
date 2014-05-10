<?php

namespace Serendipity\Metatron\Command\Diag;

use Serendipity\Metatron\Application;
use Serendipity\Metatron\Command\PHPUnit\TestCase;
use Serendipity\Metatron\Model\Config;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class VersionCommandTest
 */
class VersionCommandTest extends TestCase
{
    /**
     * @covers VersionCommand::execute
     */
    public function testExecute()
    {
        $config = new Config(S9Y_INCLUDE_PATH . 'Metatron/tests/Resources/config.yml');
        $application = new Application($config);
        $application->setConfig('versionInstalled', '2.0-alpha1');
        $application->add(new VersionCommand());

        $command = $application->find('diag:version');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('/2.0-alpha1/', $commandTester->getDisplay());
    }
}
