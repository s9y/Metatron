<?php

namespace Serendipity\Metatron\Command\User;

use Serendipity\Metatron\Application;
use Serendipity\Metatron\Command\PHPUnit\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Patchwork;

/**
 * Class PasswordCommandTest
 */
class PasswordCommandTest extends TestCase
{
    /**
     * Set up
     */
    public function setUp()
    {
        Patchwork\replace("serendipity_fetchUsers", function($x) {
            if ($x > 0) {
                $authorid = $x;
            } elseif ($x < 0) {
                return null;
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
        Patchwork\replace("serendipity_set_user_var", function () {
            return true;
        });
    }

    /**
     * No username or userid given
     * @covers PasswordCommand::execute
     */
    public function testExecuteMissingArguments()
    {
        $this->setExpectedException('RuntimeException', 'Not enough arguments');
        $application = new Application();
        $application->add(new PasswordCommand());
        $command = $application->find('user:password');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));
    }

    /**
     * User does not exist
     * @covers PasswordCommand::execute
     */
    public function testExecuteUserNotFound()
    {
        $this->setExpectedException('RuntimeException', 'User not found!');
        $application = new Application();
        $application->add(new PasswordCommand());
        $command = $application->find('user:password');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'username' => -1));
    }

    /**
     * User exists, and passwords match -> new password is set
     * @covers PasswordCommand::execute
     */
    public function testExecuteWithUseridAndPasswordsMatching()
    {
        $application = new Application();
        $application->add(new PasswordCommand());
        $command = $application->find('user:password');

        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askHiddenResponse'));
        $dialog->expects($this->at(0))->method('askHiddenResponse')->will($this->returnValue('123456'));
        $dialog->expects($this->at(1))->method('askHiddenResponse')->will($this->returnValue('123456'));
        $command->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'username' => 11));
        $this->assertRegExp('/New password set for user "johndoe"/', $commandTester->getDisplay());
    }

    /**
     * User exists, but entered passwords don't match
     * @covers PasswordCommand::execute
     */
    public function testExecuteWithUseridAndPasswordsNotMatching()
    {
        $this->setExpectedException('RuntimeException', 'Passwords do not match. Please try again.');
        $application = new Application();
        $application->add(new PasswordCommand());
        $command = $application->find('user:password');

        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askHiddenResponse'));
        $dialog->expects($this->at(0))->method('askHiddenResponse')->will($this->returnValue('123456'));
        $dialog->expects($this->at(1))->method('askHiddenResponse')->will($this->returnValue('654321'));
        $command->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'username' => 11));
    }
}
