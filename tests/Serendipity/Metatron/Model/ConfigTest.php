<?php
/**
 * Created by PhpStorm.
 * User: mattsches
 * Date: 07.03.14
 * Time: 19:21
 */

namespace Serendipity\Metatron\Model;

/**
 * Class ConfigTest
 * @package Serendipity\Metatron\Model
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Config
     */
    protected $object;

    /**
     * @var string
     */
    protected $configFilename;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->configFilename = __DIR__ . '/../../../Resources/config.yml';
        $this->object = new Config($this->configFilename);
    }

    /**
     * @test
     */
    public function initialize()
    {
        $this->assertInstanceOf('Serendipity\Metatron\Model\Config', $this->object);
    }

    /**
     * @test
     */
    public function getShouldReturnNullIfOptionIsNotAllowed()
    {
        $this->assertNull($this->object->get('bar'));
    }

    /**
     * @todo
     * @test
     */
    public function moreTEsts()
    {

    }
}
