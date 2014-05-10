<?php

require_once('vendor/autoload.php');

if (!defined('S9Y_INCLUDE_PATH')) {
    define('S9Y_INCLUDE_PATH', './');
}

require_once(S9Y_INCLUDE_PATH . 'serendipity_config_local.inc.php');
require_once(S9Y_INCLUDE_PATH . 'serendipity_config.inc.php');

use Serendipity\Metatron\Model\Config;
use Serendipity\Metatron\Application;

$config = new Config(S9Y_INCLUDE_PATH . 'metatron_config.yml');
$application = new Application($config);
$application->run();
return $application;
