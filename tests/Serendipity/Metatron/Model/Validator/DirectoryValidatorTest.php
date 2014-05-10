<?php
/**
 * Created by PhpStorm.
 * User: mattsches
 * Date: 07.03.14
 * Time: 19:16
 */

namespace Serendipity\Metatron\Model\Validator;

/**
 * Class DirectoryValidatorTest
 * @package Serendipity\Metatron\Model\Validator
 */
class DirectoryValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DirectoryValidator
     */
    protected $object;

    /**
     *
     */
    protected function setUp()
    {
        $this->object = new DirectoryValidator();
    }

    /**
     * @expectedException \Exception
     * @test
     */
    public function shouldThrowExceptionIfDirectoryDoesNotExist()
    {
        $directory = __DIR__ . '/bar/lumumba';
        $this->object->validate($directory);
    }
}
