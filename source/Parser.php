<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-11-26
 */
namespace Net\Bazzline\Component\Cli\Arguments;

class Parser
{
    /** @var array */
    private $flags;

    /** @var array */
    private $lists;

    /** @var array */
    private $values;

    public function __construct()
    {
        $this->initiate();
    }

    /**
     * @param array $arguments
     */
    public function parse(array $arguments)
    {
        $this->initiate();

        foreach ($arguments as $argument) {
            $this->addToFittingCollection($argument);
        }
    }

    /**
     * @return array
     */
    public function getFlags()
    {
        return $this->flags;
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
     * @param string $name
     * @param mixed $value
     */
    private function addToList($name, $value)
    {
        $value = trim($value, '"'); //remove >"< if exists

        if (isset($this->lists[$name])) {
            $collection = $this->lists[$name];
        } else {
            $collection = array();
        }

        $collection[]       = $value;
        $this->lists[$name] = $collection;
    }

    /**
     * @param string $string
     * @param string $search
     * @return bool
     */
    private function contains($string, $search)
    {
        if (strlen($search) === 0) {
            $contains = false;
        } else {
            $contains = !(strpos($string, $search) === false);
        }

        return $contains;
    }

    private function handleLongNameListOrFlag($argument)
    {
        if ($this->contains($argument, '=')) {
            $position   = strpos($argument, '=');
            $name       = substr($argument, 0, $position);
            $value      = substr($argument, ($position + 1));
            $this->addToList($name, $value);
        } else {
            $this->flags[] = $argument;
        }
    }

    private function handleShortNameListOrFlag($argument)
    {
        $containsEqualCharacter             = ($this->contains($argument, '='));
        $equalCharacterIsOnSecondPosition   = (strpos($argument, '=') === 1);
        $isShortNameList                    = ($containsEqualCharacter
            && $equalCharacterIsOnSecondPosition);

        if ($isShortNameList) {
            $name   = substr($argument, 0, 1);
            $value  = substr($argument, 2);
            $this->addToList($name, $value);
        } else if (!$containsEqualCharacter) {
            $length = strlen($argument);
            $iterator = 0;
            while ($iterator < $length) {
                $this->flags[] = $argument{$iterator};
                ++$iterator;
            }
        }
    }

    private function initiate()
    {
        $this->flags    = array();
        $this->lists    = array();
        $this->values   = array();
    }

    /**
     * @param $argument
     */
    private function addToFittingCollection($argument)
    {
        if ($this->hasLengthOf($argument, 1)) {
            $this->values[] = $argument;
        } else if ($this->startsWith($argument, '--')) {
            $argument = substr($argument, 2);
            $this->handleLongNameListOrFlag($argument);
        } else if ($this->startsWith($argument, '-')) {
            $argument = substr($argument, 1);
            $this->handleShortNameListOrFlag($argument);
        } else {
            $this->values[] = $argument;
        }
    }

    /**
     * @param string $string
     * @param string $start
     * @return bool
     */
    private function startsWith($string, $start)
    {
        return (strncmp($string, $start, strlen($start)) === 0);
    }

    /**
     * @param string $string
     * @param int $expectedLength
     * @return bool
     */
    private function hasLengthOf($string, $expectedLength)
    {
        $length         = strlen($string);
        $hasLengthOf    = ($length == $expectedLength);

        return $hasLengthOf;
    }
}
