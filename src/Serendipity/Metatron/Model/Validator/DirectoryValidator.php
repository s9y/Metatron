<?php
/**
 * Created by PhpStorm.
 * User: mattsches
 * Date: 07.03.14
 * Time: 18:33
 */

namespace Serendipity\Metatron\Model\Validator;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class DirectoryValidator
 * @package Serendipity\Metatron\Model\Validator
 */
class DirectoryValidator implements ValidatorInterface
{
    /**
     * @param string $input
     * @return bool
     * @throws \Exception
     */
    public function validate($input)
    {
        $fs = new Filesystem();
        if ($fs->exists($input) === false || is_dir($input) === false || is_writable($input) === false) {
            throw new \Exception("Directory " . $input . " does not exist or is not writable.");
        }
        return true;
    }
} 
