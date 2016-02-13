<?php
/**
 * @author: stev leibelt <artodeto@bazzline.net>
 * @since: 2015-04-16
 */

namespace Net\Bazzline\Component\Cli\Arguments;

class Arguments
{
    /** @var array */
    private $arguments;

    /** @var array */
    private $flags;

    /** @var array */
    private $lists;

    /** @var Parser */
    private $parser;

    /** @var array */
    private $values;

    /**
     * @param null|array $argv
     * @param boolean $removeFirstArgument
     */
    public function __construct($argv = null, $removeFirstArgument = true)
    {
        $this->parser = new Parser();

        if (is_array($argv)) {
            $this->parseArguments($argv, $removeFirstArgument);
        } else {
            $this->setArgumentsFromParser($this->parser);
        }
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return array
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @param string $name
     * @return null|array
     */
    public function getList($name)
    {
        return (isset($this->lists[$name]))
            ? $this->lists[$name]
            : null;
    }

    /**
     * @return int
     */
    public function getNumberOfArguments()
    {
        return (count($this->arguments));
    }

    /**
     * @return int
     */
    public function getNumberOfFlags()
    {
        return (count($this->flags));
    }

    /**
     * @return int
     */
    public function getNumberOfLists()
    {
        return (count($this->lists));
    }

    /**
     * @return int
     */
    public function getNumberOfValues()
    {
        return (count($this->values));
    }

    /**
     * @return array
     */
    public function getLists()
    {
        return $this->lists;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @return bool
     */
    public function hasArguments()
    {
        return (!empty($this->arguments));
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasFlag($name)
    {
        return (in_array($name, $this->flags));
    }

    /**
     * @return bool
     */
    public function hasFlags()
    {
        return (!empty($this->flags));
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasList($name)
    {
        return (isset($this->lists[$name]));
    }

    /**
     * @return bool
     */
    public function hasLists()
    {
        return (!empty($this->lists));
    }

    /**
     * @return bool
     */
    public function hasValues()
    {
        return (!empty($this->values));
    }

    /**
     * @param array $argv
     * @param boolean $removeFirstArgument
     * @return $this
     */
    public function parseArguments(array $argv, $removeFirstArgument = true)
    {
        if ($removeFirstArgument) {
            array_shift($argv);
        }

        $this->arguments = $argv;
        $this->parser->parse($this->arguments);
        $this->setArgumentsFromParser($this->parser);

        return $this;
    }

    /**
     * @return array
     */
    public function convertToArray()
    {
        return $this->getArguments();
    }

    /**
     * @return string
     */
    public function convertToString()
    {
        return implode(' ', $this->convertToArray());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->convertToString();
    }

    /**
     * @param Parser $parser
     */
    private function setArgumentsFromParser(Parser $parser)
    {
        $this->flags    = $parser->getFlags();
        $this->lists    = $parser->getLists();
        $this->values   = $parser->getValues();
    }
}