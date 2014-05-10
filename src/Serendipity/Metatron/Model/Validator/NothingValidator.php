<?php
/**
 * Created by PhpStorm.
 * User: mattsches
 * Date: 07.03.14
 * Time: 18:33
 */

namespace Serendipity\Metatron\Model\Validator;

/**
 * Class NothingValidator
 * @package Serendipity\Metatron\Model\Validator
 */
class NothingValidator implements ValidatorInterface
{
    /**
     * Always returns true
     *
     * @param mixed $input
     * @return bool
     */
    public function validate($input)
    {
        return true;
    }
} 
