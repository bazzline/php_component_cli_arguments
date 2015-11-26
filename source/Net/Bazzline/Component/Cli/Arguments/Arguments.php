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

    /** @var Collection */
    private $flags;

    /** @var Collection */
    private $lists;

    /** @var Parser */
    private $parser;

    /** @var Collection */
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
            $this->bindValuesFromGenerator();
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
     * @param bool $convertCollectionToArray
     * @return array|Collection
     */
    public function getFlags($convertCollectionToArray = true)
    {
        return (
            $this->convertCollectionToArrayIfNeeded(
                $this->flags,
                $convertCollectionToArray
            )
        );
    }

    /**
     * @param string $name
     * @param bool $convertCollectionToArray
     * @return null|Collection
     */
    public function getList($name, $convertCollectionToArray = true)
    {
        $list = $this->lists->offsetGet($name);

        if ($list instanceof Collection) {
            $return = (
                $this->convertCollectionToArrayIfNeeded(
                    $list,
                    $convertCollectionToArray
                )
            );
        } else {
            $return = null;
        }

        return $return;
    }

    /**
     * @param bool $convertCollectionToArray
     * @return array|Collection
     */
    public function getLists($convertCollectionToArray = true)
    {
        return (
            $this->convertCollectionToArrayIfNeeded(
                $this->lists,
                $convertCollectionToArray
            )
        );
    }

    /**
     * @param bool $convertCollectionToArray
     * @return array|Collection
     */
    public function getValues($convertCollectionToArray = true)
    {
        return (
            $this->convertCollectionToArrayIfNeeded(
                $this->values,
                $convertCollectionToArray
            )
        );
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
        return $this->flags->containsValue($name);
    }

    /**
     * @return bool
     */
    public function hasFlags()
    {
        return (!$this->flags->isEmpty());
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasList($name)
    {
        return $this->lists->containsKey($name);
    }

    /**
     * @return bool
     */
    public function hasLists()
    {
        return (!$this->lists->isEmpty());
    }

    /**
     * @return bool
     */
    public function hasValues()
    {
        return (!$this->values->isEmpty());
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
        $this->parse($this->arguments);
        $this->bindValuesFromGenerator();

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

    private function parse(array $arguments)
    {
        $parser = $this->parser;

        $parser->parse($arguments);
    }

    private function bindValuesFromGenerator()
    {
        $parser = $this->parser;

        $this->flags    = $parser->getFlags();
        $this->lists    = $parser->getLists();
        $this->values   = $parser->getValues();
    }

    /**
     * @param Collection $collection
     * @param bool $isNeeded
     * @return array|Collection
     */
    private function convertCollectionToArrayIfNeeded(Collection $collection, $isNeeded)
    {
        return (
            $isNeeded
                ? $collection->convertToArray()
                : $collection
        );
    }
}