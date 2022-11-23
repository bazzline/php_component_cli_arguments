<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-11-26
 */
namespace Net\Bazzline\Component\Cli\Arguments;

class Parser
{
    private array $flags;
    private array $lists;
    private array $values;

    public function __construct()
    {
        $this->initiate();
    }

    public function parse(array $arguments): void
    {
        $this->initiate();

        foreach ($arguments as $argument) {
            $this->addToFittingCollection($argument);
        }
    }

    public function getFlags(): array
    {
        return $this->flags;
    }

    public function getLists(): array
    {
        return $this->lists;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    private function addToList(string $name, mixed $value): void
    {
        $value = trim($value, '"'); //remove >"< if exists

        $collection = $this->lists[$name] ?? [];

        $collection[]       = $value;
        $this->lists[$name] = $collection;
    }

    private function contains(string $string, string $search): bool
    {
        if (strlen($search) === 0) {
            $contains = false;
        } else {
            $contains = !(!str_contains($string, $search));
        }

        return $contains;
    }

    private function handleLongNameListOrFlag(string $argument): void
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

    private function handleShortNameListOrFlag(string $argument): void
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
                $this->flags[] = $argument[$iterator];
                ++$iterator;
            }
        }
    }

    private function initiate(): void
    {
        $this->flags    = [];
        $this->lists    = [];
        $this->values   = [];
    }

    private function addToFittingCollection(string $argument): void
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

    private function startsWith(string $string, string $start): bool
    {
        return (strncmp($string, $start, strlen($start)) === 0);
    }

    private function hasLengthOf(string $string, int $expectedLength): bool
    {
        $length = strlen($string);

        return ($length == $expectedLength);
    }
}
