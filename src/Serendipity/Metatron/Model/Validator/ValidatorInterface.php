<?php
/**
 * Created by PhpStorm.
 * User: mattsches
 * Date: 07.03.14
 * Time: 18:33
 */

namespace Serendipity\Metatron\Model\Validator;

/**
 * Interface ValidatorInterface
 * @package Serendipity\Metatron\Model\Validator
 */
interface ValidatorInterface
{
    /**
     * @param $input
     * @return mixed
     */
    public function validate($input);
} 
