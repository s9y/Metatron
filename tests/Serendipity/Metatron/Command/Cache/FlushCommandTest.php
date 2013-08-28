<?php

namespace Serendipity\Metatron\Command\Cache;

use Serendipity\Metatron\Application;
use Serendipity\Metatron\Command\PHPUnit\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class FlushCommandTest
 */
class FlushCommandTest extends TestCase
{
    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * Set up
     */
    public function setUp()
    {
        parent::setUp();
        $this->cacheDir = 'metatron/tests/data/cachedir';
        mkdir(S9Y_INCLUDE_PATH . $this->cacheDir);
        touch(S9Y_INCLUDE_PATH . $this->cacheDir . '/foo.php');
        touch(S9Y_INCLUDE_PATH . $this->cacheDir . '/bar.php');
        touch(S9Y_INCLUDE_PATH . $this->cacheDir . '/.empty');
    }

    /**
     * Tear down
     */
    public function tearDown()
    {
        parent::tearDown();
        if (file_exists(S9Y_INCLUDE_PATH . $this->cacheDir . '/foo.php')) {
            unlink(S9Y_INCLUDE_PATH . $this->cacheDir . '/foo.php');
        }
        if (file_exists(S9Y_INCLUDE_PATH . $this->cacheDir . '/bar.php')) {
            unlink(S9Y_INCLUDE_PATH . $this->cacheDir . '/bar.php');
        }
        if (file_exists(S9Y_INCLUDE_PATH . $this->cacheDir . '/.empty')) {
            unlink(S9Y_INCLUDE_PATH . $this->cacheDir . '/.empty');
        }
        rmdir(S9Y_INCLUDE_PATH . $this->cacheDir);
    }

    /**
     * @covers FlushCommand::execute
     */
    public function testExecute()
    {
        $application = new Application();
        $application->add(new FlushCommand());

        $command = $application->find('cache:flush');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'dir' => $this->cacheDir));

        $this->assertRegExp('/Successfully flushed cache directory/', $commandTester->getDisplay());
        $this->assertFileNotExists(S9Y_INCLUDE_PATH . $this->cacheDir . '/foo.php');
        $this->assertFileNotExists(S9Y_INCLUDE_PATH . $this->cacheDir . '/bar.php');
        $this->assertFileExists(S9Y_INCLUDE_PATH . $this->cacheDir . '/.empty');
        $this->assertTrue(is_dir(S9Y_INCLUDE_PATH . $this->cacheDir));
    }
}
