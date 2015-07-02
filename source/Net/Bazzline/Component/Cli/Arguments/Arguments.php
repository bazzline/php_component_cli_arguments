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

    /** @var array */
    private $values;

    /**
     * @param null|array $argv
     * @param boolean $removeFirstArgument
     */
    public function __construct($argv = null, $removeFirstArgument = true)
    {
        if (is_array($argv)) {
            $this->setArguments($argv, $removeFirstArgument);
        } else {
            $this->initiate();
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
        return ($this->hasFlags()) ? $this->flags : array();
    }

    /**
     * @param string $name
     * @return null|mixed
     */
    public function getList($name)
    {
        return ($this->hasList($name)) ? $this->lists[$name] : null;
    }

    /**
     * @return array
     */
    public function getLists()
    {
        return ($this->hasLists()) ? $this->lists : array();
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return ($this->hasValues()) ? $this->values : array();
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
        return in_array($name, $this->flags);
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
    public function setArguments(array $argv, $removeFirstArgument = true)
    {
        if ($removeFirstArgument) {
            array_shift($argv);
        }

        $this->initiate();
        $this->arguments = $argv;
        $this->generate($this->arguments);

        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    private function addToList($name, $value)
    {
        $value = trim($value, '"'); //remove >"< if exists

        if (isset($this->lists[$name])) {
            $this->lists[$name][] = $value;
        } else {
            $this->lists[$name] = array($value);
        }
    }

    private function generate(array $arguments)
    {
        foreach ($arguments as $argument) {
            if ($this->startsWith($argument, '--')) {
                $argument = substr($argument, 2);
                $this->handleLongNameListOrFlag($argument);
            } else if ($this->startsWith($argument, '-')) {
                $argument = substr($argument, 1);
                $this->handleShortNameListOrFlag($argument);
            } else {
                $this->values[] = $argument;
            }

        }
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

    private function initiate()
    {
        $this->arguments    = array();
        $this->flags        = array();
        $this->lists        = array();
        $this->values       = array();
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
}