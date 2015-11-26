<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-11-26
 */
namespace Net\Bazzline\Component\Cli\Arguments;

use ArrayAccess;
use InvalidArgumentException;
use Iterator;

class Collection implements ArrayAccess, Iterator
{
    /** @var array */
    private $elements;

    /** @var int */
    private $numberOfElements;

    /**
     * Collection constructor.
     * @param array $elements
     */
    public function __construct(array $elements = array())
    {
        $this->resetAll($elements);
    }

    //begin of ArrayAccess interface implementation
    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return (isset($this->elements[$offset]));
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return (
            $this->offsetExists($offset)
                ? $this->elements[$offset]
                : null
        );
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->elements[] = $value;
        } else {
            $this->elements[$offset] = $value;
        }
        $this->reset();
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->elements[$offset]);
        }
        $this->reset();
    }
    //end of ArrayAccess interface implementation

    //begin of Iterator interface implementation
    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return current($this->elements);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        next($this->elements);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return key($this->elements);
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return (isset($this->elements[$this->key()]));
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        reset($this->elements);
    }
    //end of Iterator interface implementation

    /**
     * @param array $elements
     * @param bool $referenceKeysOfProvidedElements
     * @throws InvalidArgumentException
     */
    public function addMany(array $elements, $referenceKeysOfProvidedElements = false)
    {
        if ($referenceKeysOfProvidedElements) {
            foreach ($elements as $key => $element) {
                $this->addOne($element, $key);
            }
        } else {
            foreach ($elements as $element) {
                $this->addOne($element);
            }
        }
    }

    /**
     * @param mixed $element
     * @param mixed|null $key
     * @throws InvalidArgumentException
     */
    public function addOne($element, $key = null)
    {
        if ($this->elementIsValid($element)) {
            $this->offsetSet($key, $element);
        } else {
            throw new InvalidArgumentException(
                'provided element is invalid'
            );
        }
    }

    /**
     * @param mixed $key
     * @return bool
     */
    public function containsKey($key)
    {
        return ($this->offsetExists($key));
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function containsValue($value)
    {
        return (in_array($value, $this->elements));
    }

    /**
     * @return int
     */
    public function getNumberOfElements()
    {
        return $this->numberOfElements;
    }

    /**
     * @param null|mixed $key
     * @return mixed
     */
    public function getOne($key = null)
    {
        return (
            is_null($key)
                ? $this->current()
                : $this->offsetGet($key)
        );
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return ($this->numberOfElements === 0);
    }

    /**
     * @return array
     */
    public function convertToArray()
    {
        $array = array();

        foreach ($this->elements as $key => $value) {
            if ($value instanceof Collection) {
                $value = $value->convertToArray();
            }

            $array[$key] = $value;
        }

        return $array;
    }

    /**
     * @param string $separator
     * @return string
     */
    public function convertToString($separator = ' ')
    {
        return (implode($separator, $this->elements));
    }

    /**
     * @param array $elements
     * @throws InvalidArgumentException
     */
    public function resetAll(array $elements)
    {
        $this->elements = array();
        $this->rewind();

        if (empty($elements)) {
            $this->calculateNumberOfElements();
        } else {
            $this->addMany($elements);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->convertToString();
    }

    /**
     * overwrite this if you want to, default is always returning a true
     * @param mixed $element
     * @return bool
     */
    protected function elementIsValid($element)
    {
        return true;
    }

    private function calculateNumberOfElements()
    {
        $this->numberOfElements = count($this->elements);
    }

    private function reset()
    {
        $this->calculateNumberOfElements();
        $this->rewind();
    }
}