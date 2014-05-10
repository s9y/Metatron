<?php

namespace Serendipity\Metatron\Command\Comment;

use Serendipity\Metatron\Application;
use Serendipity\Metatron\Command\PHPUnit\TestCase;
use Serendipity\Metatron\Model\Config;
use Symfony\Component\Console\Tester\CommandTester;
use Patchwork;

/**
 * Class ApproveCommandTest
 */
class ApproveCommandTest extends TestCase
{
    /**
     * @covers ListCommand::execute
     * @return void
     */
    public function testMultiplePendingCommentsAreShownInTable()
    {
        $this->replaceQuery(2);
        $this->replaceApproveComment(false);
        $commandTester = $this->getCommandTester(3);
        $this->assertRegExp('/\| 3  \| Blog Entry Title \| Jul 21 2013, 12:09 \| John Doe \| john.doe@example.com \| This is a comment      \| NORMAL \| pending \|/', $commandTester->getDisplay());
        $this->assertRegExp('/\| 5  \| Blog Entry Title \| Jul 21 2013, 12:09 \| John Doe \| john.doe@example.com \| This is a comment, too \| NORMAL \| pending \|/', $commandTester->getDisplay());
    }

    /**
     * @covers ListCommand::execute
     * @return void
     */
    public function testSelectedCommentIsNotApproved()
    {
        $this->replaceQuery(2);
        $this->replaceApproveComment(false);
        Patchwork\replace('serendipity_approveComment', function() {
            return false;
        });
        $commandTester = $this->getCommandTester(3);
        $this->assertRegExp('/Comment 3 could not be approved./', $commandTester->getDisplay());
    }

    /**
     * @covers ListCommand::execute
     * @return void
     */
    public function testSelectedCommentIsApproved()
    {
        $this->replaceQuery(2);
        $this->replaceApproveComment(true);
        $commandTester = $this->getCommandTester(3);
        $this->assertRegExp('/Comment 3 approved./', $commandTester->getDisplay());
    }

    /**
     * @covers ListCommand::execute
     * @return void
     */
    public function testSingleCommentIsApproved()
    {
        $this->replaceQuery(1);
        $this->replaceApproveComment(true);
        $commandTester = $this->getCommandTester(null, 3);
        $this->assertRegExp('/Comment 3 approved./', $commandTester->getDisplay());
    }

    /**
     * @covers ListCommand::execute
     */
    public function testNoPendingCommentsFound()
    {
        $this->replaceQuery(0);
        $this->replaceApproveComment(false);
        $commandTester = $this->getCommandTester(null, 13);
        $this->assertRegExp('/No pending comments found./', $commandTester->getDisplay());
    }

    /**
     * Helper methods
     */

    /**
     * @param null|int $dialogReturnValue
     * @param null|int $commentId
     * @return CommandTester
     */
    protected function getCommandTester($dialogReturnValue = null, $commentId = null)
    {
        $config = new Config(S9Y_INCLUDE_PATH . 'Metatron/tests/Resources/config.yml');
        $application = new Application($config);
        $application->add(new ApproveCommand());
        $command = $application->find('comment:approve');
        $commandTester = new CommandTester($command);
        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askAndValidate'));
        if (!empty($dialogReturnValue)) {
            $dialog->expects($this->at(0))->method('askAndValidate')->will($this->returnValue($dialogReturnValue));
        }
        $command->getHelperSet()->set($dialog, 'dialog');
        $testerArgs = array('command' => $command->getName());
        if (!empty($commentId)) {
            $testerArgs['commentid'] = $commentId;
        }
        $commandTester->execute($testerArgs);

        return $commandTester;
    }

    /**
     * @param int $resultEntries How many results should be returend by the replaced function
     */
    protected function replaceQuery($resultEntries = 1)
    {
        $result = array(
            array(
                'id' => '3',
                'entry_id' => '2',
                'parent_id' => '0',
                'timestamp' => '1374401343',
                'title' => 'Blog Entry Title',
                'author' => 'John Doe',
                'email' => 'john.doe@example.com',
                'url' => '',
                'ip' => '127.0.0.1',
                'body' => 'This is a comment',
                'type' => 'NORMAL',
                'subscribed' => 'false',
                'status' => 'pending',
                'referer' => 'http://s9y2.local/serendipity_admin.php',
            ),
        );
        if ($resultEntries == 2) {
            array_push(
                $result,
                array(
                    'id' => '5',
                    'entry_id' => '2',
                    'parent_id' => '0',
                    'timestamp' => '1374401343',
                    'title' => 'Blog Entry Title',
                    'author' => 'John Doe',
                    'email' => 'john.doe@example.com',
                    'url' => '',
                    'ip' => '127.0.0.1',
                    'body' => 'This is a comment, too',
                    'type' => 'NORMAL',
                    'subscribed' => 'false',
                    'status' => 'pending',
                    'referer' => 'http://s9y2.local/serendipity_admin.php',
                )
            );
        } elseif ($resultEntries == 0) {
            $result = false;
        }
        Patchwork\replace("serendipity_db_query", function() use ($result) {
            return $result;
        });
    }

    /**
     * @param bool $returnValue
     */
    protected function replaceApproveComment($returnValue = false)
    {
        Patchwork\replace('serendipity_approveComment', function() use ($returnValue) {
            return $returnValue;
        });
    }
}
