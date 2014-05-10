<?php

namespace Serendipity\Metatron\Command\Backup\Db;

use Serendipity\Metatron\Application;
use Serendipity\Metatron\Command\PHPUnit\TestCase;
use Serendipity\Metatron\Model\Config;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class DumpCommandTest
 */
class DumpCommandTest extends TestCase
{
    /**
     * @var string
     */
    protected $backupDir;

    /**
     * Set up
     */
    public function setUp()
    {
        parent::setUp();
        $this->backupDir = S9Y_INCLUDE_PATH . 'Metatron/tests/data/backup';
        mkdir($this->backupDir);
    }

    /**
     * Tear down
     */
    public function tearDown()
    {
        if (is_dir($this->backupDir)) {
            array_map('unlink', glob($this->backupDir . '/*.*'));
            rmdir($this->backupDir);
        }
        parent::tearDown();
    }

    /**
     * @covers DumpCommand::execute
     * @test
     * @dataProvider dataProvider
     */
    public function testBackup($input, $glob)
    {
        $commandTester = $this->getCommandTester();
        $commandTester->execute($input);
        $this->assertContains('Wrote backup to', $commandTester->getDisplay());
        $files = glob($glob);
        $this->assertCount(1, $files);
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return array(
            array(
                array('command' => DumpCommand::NAME, '--' . DumpCommand::OPTION_TYPE => DumpCommand::TYPE_SCHEMA),
                S9Y_INCLUDE_PATH . 'Metatron/tests/data/backup' . '/test_schema_*.sql',
            ),
            array(
                array('command' => DumpCommand::NAME, '--' . DumpCommand::OPTION_TYPE => DumpCommand::TYPE_FULL),
                S9Y_INCLUDE_PATH . 'Metatron/tests/data/backup' . '/test_full_*.sql',
            ),
            array(
                array(
                    'command' => DumpCommand::NAME,
                    '--' . DumpCommand::OPTION_TYPE => DumpCommand::TYPE_SCHEMA,
                    '--' . DumpCommand::OPTION_GZIPPED => DumpCommand::OPTION_GZIPPED,
                ),
                S9Y_INCLUDE_PATH . 'Metatron/tests/data/backup' . '/test_schema_*.sql.gz',
            ),
            array(
                array(
                    'command' => DumpCommand::NAME,
                    '--' . DumpCommand::OPTION_TYPE => DumpCommand::TYPE_FULL,
                    '--' . DumpCommand::OPTION_GZIPPED => DumpCommand::OPTION_GZIPPED,
                ),
                S9Y_INCLUDE_PATH . 'Metatron/tests/data/backup' . '/test_full_*.sql.gz',
            ),
        );
    }

    /**
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    protected function getCommandTester()
    {
        $config = new Config(S9Y_INCLUDE_PATH . 'Metatron/tests/Resources/config.yml');
        $config->set('backupdir', $this->backupDir);
        $application = new Application($config);
        $application->add(new DumpCommand());
        $command = $application->find('backup:db:dump');
        $commandTester = new CommandTester($command);
        return $commandTester;
    }
}
