<?php

require_once(S9Y_INCLUDE_PATH . 'metatron/tests/AbstractTest.php');

use Serendipity\Metatron\Application;
use Serendipity\Metatron\Command\User\ListCommand;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class ListCommandTest
 */
class ListCommandTest extends AbstractTest
{
    /**
     * Set up
     */
    public function setUp()
    {
        Patchwork\replace("serendipity_fetchUsers", function($x) {
            if ($x > 0) {
                $authorid = $x;
            } else {
                $authorid = 1;
            }
            return array(
                array(
                    'authorid' => $authorid,
                    'username' => 'johndoe',
                    'realname' => 'John Doe',
                    'email' => 'john.doe@example.com',
                    'userlevel' => 1,
                ),
            );
        });
        Patchwork\replace("serendipity_fetchAuthor", function() {
            return array(
                array(
                    'authorid' => 1,
                    'username' => 'johndoe',
                    'realname' => 'John Doe',
                    'email' => 'john.doe@example.com',
                    'userlevel' => 1,
                ),
            );
        });
    }

    /**
     * @covers ListCommand::execute
     */
    public function testExecute()
    {
        $application = new Application();
        $application->add(new ListCommand());
        $command = $application->find('user:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));
        $this->assertRegExp('/| 1  | johndoe  | John Doe  | john.doe@example.com | Chief editor |/', $commandTester->getDisplay());
    }

    /**
     * @covers ListCommand::execute
     */
    public function testExecuteWithUsername()
    {
        $application = new Application();
        $application->add(new ListCommand());
        $command = $application->find('user:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'username' => 'johndoe'));
        $this->assertRegExp('/| 1  | johndoe  | John Doe  | john.doe@example.com | Chief editor |/', $commandTester->getDisplay());
    }

    /**
     * @covers ListCommand::execute
     */
    public function testExecuteWithUserid()
    {
        $application = new Application();
        $application->add(new ListCommand());
        $command = $application->find('user:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'username' => 11));
        $this->assertRegExp('/| 11  | johndoe  | John Doe  | john.doe@example.com | Chief editor |/', $commandTester->getDisplay());
    }
}
