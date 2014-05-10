<?php
/**
 * Created by PhpStorm.
 * User: mattsches
 * Date: 09.05.14
 * Time: 22:50
 */

namespace Serendipity\Metatron\Exception;

/**
 * Class InvalidKeyException
 * @package Serendipity\Metatron\Exception
 */
class InvalidKeyException extends \Exception
{
    /**
     * @return string
     */
    public function __toString()
    {
        return 'Option \'' . $this->message . '\' is not allowed.';
    }
} 
