<?php
/**
 * Created by PhpStorm.
 * User: mattsches
 * Date: 07.03.14
 * Time: 19:14
 */

namespace Serendipity\Metatron\Model\Validator;

/**
 * Class NothingValidatorTest
 * @package Serendipity\Metatron\Model\Validator
 */
class NothingValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NothingValidator
     */
    protected $object;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->object = new NothingValidator();
    }

    /**
     * @test
     */
    public function testValidate()
    {
        $this->assertTrue($this->object->validate('whatever'));
    }
}
