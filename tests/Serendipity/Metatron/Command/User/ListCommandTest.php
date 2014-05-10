<?php

namespace Serendipity\Metatron\Command\User;

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
     * @param mixed $argument
     * @param string $expectedRegExp
     *
     * @covers ListCommand::execute
     * @dataProvider providerTestExecute
     */
    public function testExecute($argument, $expectedRegExp)
    {
        $config = new Config(S9Y_INCLUDE_PATH . 'Metatron/tests/Resources/config.yml');
        $application = new Application($config);
        $application->add(new ListCommand());
        $command = $application->find('user:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'username' => $argument));
        $this->assertRegExp($expectedRegExp, $commandTester->getDisplay());
    }

    /**
     * Data provider for testExecute
     *
     * @return array
     */
    public function providerTestExecute()
    {
        return array(
            array(
                null,
                '/\| 1  \| johndoe  \| John Doe  \| john.doe@example.com \| Chief editor \|/',
            ),
            array(
                'johndoe',
                '/\| 1  \| johndoe  \| John Doe  \| john.doe@example.com \| Chief editor \|/',
            ),
            array(
                11,
                '/\| 11 \| johndoe  \| John Doe  \| john.doe@example.com \| Chief editor \|/',
            ),
        );
    }
}
