<?php

namespace Serendipity\Metatron\Model;

use Serendipity\Metatron\Exception\InvalidKeyException;
use Serendipity\Metatron\Model\Validator\ValidatorInterface;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

/**
 * Class Config
 * @package Serendipity\Metatron
 */
class Config
{
    /**
     * Don't forget to add new options and their validators here
     *
     * @var array
     */
    protected $optionValidators = array(
        'backupdir' => 'Directory',
    );

    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var string
     */
    protected $metatronConfigFile;

    /**
     * Constructor
     *
     * @param string
     */
    public function __construct($configFile)
    {
        $this->metatronConfigFile = $configFile;
        $this->readMetatronConfig();
    }

    /**
     * @return void
     */
    public function save()
    {
        $this->writeMetatronConfig();
    }

    /**
     * @param string $key
     * @return null|mixed
     */
    public function get($key)
    {
        if (!isset($this->config[$key])) {
            return null;
        }
        $this->validateOption($key, $this->config[$key]);
        return $this->config[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return null
     */
    public function set($key, $value)
    {
        $this->validateOption($key, $value);
        $this->config[$key] = $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @throws \Exception
     * @return bool
     */
    protected function validateOption($key, $value = null)
    {
        if (!array_key_exists($key, $this->optionValidators)) {
            throw new InvalidKeyException('Option \'' . $key . '\' is not allowed.');
        }
        if ($value !== null) {
            $validatorName = 'Serendipity\Metatron\Model\Validator\\' . $this->optionValidators[$key] . 'Validator';
            /** @var ValidatorInterface $validator */
            $validator = new $validatorName;
            $validator->validate($value);
        }
        return true;
    }

    /**
     * @return void
     */
    protected function readMetatronConfig()
    {
        $yaml = new Parser();
        try {
            $contents = file_get_contents($this->metatronConfigFile);
            if (empty($contents)) {
                $this->config = array();
            } else {
                $this->config = $yaml->parse($contents);
            }
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
            die();
        }
    }

    /**
     * @return void
     */
    protected function writeMetatronConfig()
    {
        $dumper = new Dumper();
        $yaml = $dumper->dump($this->config, 2);
        file_put_contents($this->metatronConfigFile, $yaml);
    }

    /**
     * @return array
     */
    public function getOptionValidators()
    {
        return $this->optionValidators;
    }
}
