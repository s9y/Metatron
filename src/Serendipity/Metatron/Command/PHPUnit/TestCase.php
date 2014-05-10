<?php

namespace Serendipity\Metatron\Command\PHPUnit;

use Patchwork;

global $serendipity, $s9y;

$serendipity = array();
$serendipity['versionInstalled']    = '1.7';
$serendipity['dbName']              = 'test';
$serendipity['dbPrefix']            = 's9ytest_';
$serendipity['dbHost']              = 'test';
$serendipity['dbUser']              = 'test';
$serendipity['dbPass']              = 'test';
$serendipity['dbType']              = 'mysqli';
$serendipity['dbPersistent']        = false;
$serendipity['dbCharset']           = 'utf8';
$serendipity['production']          = false;
$serendipity['lang']                = 'en';
$serendipity['charset']             = 'UTF-8/';
$serendipity['defaultTemplate']     = '2k11';
$serendipity['serendipityHTTPPath'] = '/';
$serendipity['baseUrl']             = 'http://s9y.local/';
$serendipity['authorid']            = '1';
$serendipity['authorid']            = '1';
$serendipity['no_create']           = false;
$serendipity['languages']           = array();
$s9y = $serendipity;

require_once S9Y_INCLUDE_PATH . 'lang/serendipity_lang_en.inc.php';
require_once S9Y_INCLUDE_PATH . 'include/functions_config.inc.php';
require_once S9Y_INCLUDE_PATH . 'include/functions_permalinks.inc.php';
require_once S9Y_INCLUDE_PATH . 'include/functions.inc.php';
require_once S9Y_INCLUDE_PATH . 'include/db/db.inc.php';
//require_once S9Y_INCLUDE_PATH . 'include/db/' . DB_TYPE . '.inc.php';

//serendipity_initPermalinks();

/**
 * PluginTest
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Set up
     */
    public function setUp()
    {
        if (php_sapi_name() !== PHP_SAPI) {
            die('not allowed');
        }
    }

    /**
     * Tear down
     */
    public function tearDown()
    {
        Patchwork\undoAll();
    }
}
