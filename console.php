<?php

require_once('vendor/autoload.php');

if (!defined('S9Y_INCLUDE_PATH')) {
    define('S9Y_INCLUDE_PATH', './');
}

require_once(S9Y_INCLUDE_PATH . 'serendipity_config_local.inc.php');
require_once(S9Y_INCLUDE_PATH . 'serendipity_config.inc.php');

use Serendipity\Metatron\Application;

$application = new Application();
$application->run();
return $application;
