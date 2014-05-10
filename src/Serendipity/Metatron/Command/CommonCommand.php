<?php

namespace Serendipity\Metatron\Command;

use Serendipity\Metatron\Model\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

/**
 * Class CommonCommand
 * @package Serendipity\Metatron\Command
 */
class CommonCommand extends Command
{
    /**
     * @var Config
     */
    protected $config = array();

    /**
     * @param null $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    /**
     * @param string $lang
     * @return void
     */
    protected function includeLanguageFile($lang)
    {
        $languageInclude = S9Y_INCLUDE_PATH . 'lang/UTF-8/serendipity_lang_' . $lang . '.inc.php';
        if (file_exists($languageInclude)) {
            require_once(S9Y_INCLUDE_PATH . 'lang/UTF-8/serendipity_lang_' . $lang . '.inc.php');
        } else {
            require_once(S9Y_INCLUDE_PATH . 'lang/serendipity_lang_en.inc.php');
        }
    }
}
