<?php

$loader = require_once(__DIR__ . '/../vendor/autoload.php');
$loader->setUseIncludePath(true);

if (!defined('S9Y_INCLUDE_PATH')) {
    define('S9Y_INCLUDE_PATH', dirname(__FILE__) . '/../../');
}
set_include_path(dirname(__FILE__) . PATH_SEPARATOR . get_include_path());
require_once(S9Y_INCLUDE_PATH . 'Metatron/tests/patchwork.phar');
