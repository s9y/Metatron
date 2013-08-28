<?php

namespace Serendipity\Metatron\Command\Diag;

use Serendipity\Metatron\Application;
use Serendipity\Metatron\Command\PHPUnit\TestCase;
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
        $application = new Application();
        $application->setConfig('blogTitle', 'foobar');
        $application->add(new ConfigCommand());

        $command = $application->find('diag:config');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'name' => 'blogTitle'));

        $this->assertRegExp('/foobar/', $commandTester->getDisplay());
    }
}
