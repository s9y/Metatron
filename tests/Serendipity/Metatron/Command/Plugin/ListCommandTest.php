<?php

namespace Serendipity\Metatron\Command\Plugin;

use Serendipity\Metatron\Application;
use Serendipity\Metatron\Command\PHPUnit\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Patchwork;

/**
 * Class ListCommandTest
 */
class ListCommandTest extends TestCase
{
    /**
     * @covers ListCommand::execute
     */
    public function testListAllInstalledPlugins()
    {
        $mockResult = $this->getMockDbResult();
        Patchwork\replaceLater("\\serendipity_plugin_api::enum_plugins", function() use ($mockResult) {
            return $mockResult;
        });

        $application = new Application();
        $application->add(new ListCommand());
        $command = $application->find('plugin:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));
        $this->assertRegExp('/\| Markup: Serendipity +\|/', $commandTester->getDisplay());
        $this->assertRegExp('/\| serendipity_event_s9ymarkup +\|/', $commandTester->getDisplay());
        $this->assertRegExp('/\| event +\|/', $commandTester->getDisplay());
        $this->assertRegExp('/\| active +\|/', $commandTester->getDisplay());
    }

    /**
     * @covers ListCommand::execute
     */
    public function testListAllInstalledEventPlugins()
    {
        $mockResult = $this->getMockDbResult('event');
        Patchwork\replaceLater("\\serendipity_plugin_api::enum_plugins", function() use ($mockResult) {
            return $mockResult;
        });

        $application = new Application();
        $application->add(new ListCommand());
        $command = $application->find('plugin:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'type' => 'event'));
        $this->assertRegExp('/\| Markup: Serendipity \|/', $commandTester->getDisplay());
        $this->assertRegExp('/\| serendipity_event_s9ymarkup \|/', $commandTester->getDisplay());
        $this->assertRegExp('/\| event \|/', $commandTester->getDisplay());
        $this->assertRegExp('/\| active \|/', $commandTester->getDisplay());
    }

    /**
     * @covers ListCommand::execute
     */
    public function testListAllInstalledSidebarPlugins()
    {
        $mockResult = $this->getMockDbResult('sidebar');
        Patchwork\replaceLater("\\serendipity_plugin_api::enum_plugins", function() use ($mockResult) {
            return $mockResult;
        });

        $application = new Application();
        $application->add(new ListCommand());
        $command = $application->find('plugin:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'type' => 'sidebar'));
        $this->assertRegExp('/\| HTML Nugget \|/', $commandTester->getDisplay());
        $this->assertRegExp('/\| serendipity_plugin_html_nugget \|/', $commandTester->getDisplay());
        $this->assertRegExp('/\| sidebar \|/', $commandTester->getDisplay());
        $this->assertRegExp('/\| active \|/', $commandTester->getDisplay());
    }

    /**
     * @param string $type
     * @return array
     */
    protected function getMockDbResult($type = 'all')
    {
        $result = array(
            0 => array(
                0 => "serendipity_event_s9ymarkup:5533079f27018e7b0cbda83f6f5fe964",
                'name' => "serendipity_event_s9ymarkup:5533079f27018e7b0cbda83f6f5fe964",
                1 => "event",
                'placement' => "event",
                2 => "0",
                'sort_order' => "0",
                3 => "0",
                'authorid' => "0",
                4 => "",
                'path' => "",
            ),
            1 => array(
                0 => "serendipity_plugin_html_nugget:84842105b7cffd75ab0eb7cba760f97b",
                'name' => "serendipity_plugin_html_nugget:84842105b7cffd75ab0eb7cba760f97b",
                1 => "right",
                'placement' => "right",
                2 => "8",
                'sort_order' => "8",
                3 => "0",
                'authorid' => "0",
                4 => "serendipity_plugin_html_nugget",
                'path' => "serendipity_plugin_html_nugget",
            ),
        );
        if ($type == 'event') {
            return array($result[0]);
        } elseif ($type == 'sidebar') {
            return array($result[1]);
        }
        return $result;
    }
}
