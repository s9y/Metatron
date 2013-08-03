<?php

namespace Serendipity\Metatron\Command;

use Symfony\Component\Console\Command\Command;

/**
 * Class AbstractCommand
 * @package Serendipity\Metatron\Command
 */
class AbstractCommand extends Command
{

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
