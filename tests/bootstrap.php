<?php

require_once(__DIR__ . '/../vendor/autoload.php');

if (!defined('S9Y_INCLUDE_PATH')) {
    define('S9Y_INCLUDE_PATH', dirname(__FILE__) . '/../../');
}

require_once(S9Y_INCLUDE_PATH . 'metatron/tests/patchwork.phar');
