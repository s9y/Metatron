<?php

namespace Serendipity\Metatron\Exception;

use Serendipity\Metatron\Command\PHPUnit\TestCase;

/**
 * Class InvalidKeyExceptionTest
 */
class InvalidKeyExceptionTest extends TestCase
{
    /**
     * @const integer Do not throw an exception
     */
    const THROW_NONE    = 0;

    /**
     * @const integer Throw an \InvalidKeyException
     */
    const THROW_CUSTOM  = 1;

    /**
     * @const integer Throw a general \Exception
     */
    const THROW_DEFAULT = 2;

    /**
     * @dataProvider dataProvider
     */
    public function testException($avalue)
    {
        try {
            switch ($avalue) {
                case self::THROW_CUSTOM:
                    throw new InvalidKeyException('1 ist ein ungültiger Parameter', 5);
                    break;

                case self::THROW_DEFAULT:
                    throw new \Exception('2 ist kein zugelassener Parameter', 6);
                    break;

                default:
                    break;
            }
        } catch (InvalidKeyException $e) {
            $this->assertInstanceOf('Serendipity\Metatron\Exception\InvalidKeyException', $e);
            return;
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Exception', $e);
            return;
        }
        $this->assertEquals(0, $avalue);
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return array(
            array(self::THROW_CUSTOM),
            array(self::THROW_DEFAULT),
            array(self::THROW_NONE),
        );
    }
}
