<?php

namespace Serendipity\Metatron\Command\Comment;

use Serendipity\Metatron\Application;
use Serendipity\Metatron\Command\PHPUnit\TestCase;
use Serendipity\Metatron\Model\Config;
use Symfony\Component\Console\Tester\CommandTester;
use Patchwork;

/**
 * Class ListCommandTest
 */
class ListCommandTest extends TestCase
{
    /**
     * Set up
     */
    public function setUp()
    {
        Patchwork\replace("serendipity_db_query", function() {
            return array(
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
                    'status' => 'approved',
                    'referer' => 'http://s9y2.local/serendipity_admin.php',
                ),
            );
        });
    }

    /**
     * @covers ListCommand::execute
     */
    public function testExecute()
    {
        $config = new Config(S9Y_INCLUDE_PATH . 'Metatron/tests/Resources/config.yml');
        $application = new Application($config);
        $application->add(new ListCommand());
        $command = $application->find('comment:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));
        $this->assertRegExp('/\| 3  \| Blog Entry Title \| Jul 21 2013, 12:09 \| John Doe \| john.doe@example.com \| This is a comment \| NORMAL \| approved \|/', $commandTester->getDisplay());
    }
}
