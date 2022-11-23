<?php
/**
 * @author: stev leibelt <artodeto@bazzline.net>
 * @since: 2015-04-16
 */

namespace Net\Bazzline\Component\Cli\Arguments;

class Arguments
{
    private array $arguments;
    private array $flags;
    private array $lists;
    private Parser $parser;
    private array $values;

    public function __construct(array $argv = null, bool $removeFirstArgument = true)
    {
        $this->parser = new Parser();

        if (is_array($argv)) {
            $this->parseArguments($argv, $removeFirstArgument);
        } else {
            $this->setArgumentsFromParser($this->parser);
        }
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getFlags(): array
    {
        return $this->flags;
    }

    public function getList(string $name): null|array
    {
        return (isset($this->lists[$name]))
            ? $this->lists[$name]
            : null;
    }

    public function getNumberOfArguments(): int
    {
        return (count($this->arguments));
    }

    public function getNumberOfFlags(): int
    {
        return (count($this->flags));
    }

    public function getNumberOfLists(): int
    {
        return (count($this->lists));
    }

    public function getNumberOfValues(): int
    {
        return (count($this->values));
    }

    public function getLists(): array
    {
        return $this->lists;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function hasArguments(): bool
    {
        return (!empty($this->arguments));
    }

    public function hasFlag(string $name): bool
    {
        return (in_array($name, $this->flags));
    }

    public function hasFlags(): bool
    {
        return (!empty($this->flags));
    }

    public function hasList(string $name): bool
    {
        return (isset($this->lists[$name]));
    }

    public function hasLists(): bool
    {
        return (!empty($this->lists));
    }

    public function hasValues(): bool
    {
        return (!empty($this->values));
    }

    public function parseArguments(array $argv, bool $removeFirstArgument = true): self
    {
        if ($removeFirstArgument) {
            array_shift($argv);
        }

        $this->arguments = $argv;
        $this->parser->parse($this->arguments);
        $this->setArgumentsFromParser($this->parser);

        return $this;
    }

    public function convertToArray(): array
    {
        return $this->getArguments();
    }

    public function convertToString(): string
    {
        return implode(' ', $this->convertToArray());
    }

    public function __toString(): string
    {
        return $this->convertToString();
    }

    private function setArgumentsFromParser(Parser $parser): void
    {
        $this->flags    = $parser->getFlags();
        $this->lists    = $parser->getLists();
        $this->values   = $parser->getValues();
    }
}