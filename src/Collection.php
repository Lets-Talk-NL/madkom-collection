<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 12.02.16
 * Time: 15:02
 */

namespace Madkom\Collection;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Serializable;
use Traversable;

/**
 * Class Collection
 * @package Madkom\Collection
 * @author  Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class Collection implements Countable, IteratorAggregate, Serializable
{
    /**
     * @var array
     */
    protected array $elements = [];

    /**
     * Collection constructor.
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * Add the element to collection
     * @param $element
     * @return bool
     */
    public function add($element): bool
    {
        $this->elements[] = $element;

        return $this->contains($element);
    }

    /**
     * Removes element and checks if collection not contains it anymore
     * @param $element
     * @return bool
     */
    public function remove($element): bool
    {
        if (!$this->contains($element)) {
            return false;
        }
        $index = array_search($element, $this->elements, true);
        unset($this->elements[$index]);

        return !$this->contains($element);
    }

    /**
     * Return element existence in collection test result
     * @param $element
     * @return bool
     */
    public function contains($element): bool
    {
        return in_array($element, $this->elements, true);
    }

    /**
     * Iterates through all elements to exam existence by callback
     * @param callable $checker
     * @return bool
     */
    public function exists(callable $checker): bool
    {
        foreach ($this->elements as $element) {
            if ($checker($element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Iterates through all elements to exam existence by callback
     * @param callable $checker
     * @return static
     */
    public function filter(callable $checker): static
    {
        $result = new static();
        foreach ($this->elements as $element) {
            if ($checker($element)) {
                $result->add($element);
            }
        }

        return $result;
    }

    /**
     * String representation of object
     * @link  http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize(): string
    {
        return serialize($this->elements);
    }

    public function __serialize(): array
    {
        return $this->elements;
    }

    /**
     * Constructs the object
     * @link  http://php.net/manual/en/serializable.unserialize.php
     * @param string $data <p>
     *                     The string representation of the object.
     *                     </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize(string $data): void
    {
        $this->elements = unserialize($data);
    }

    public function __unserialize(array $data): void
    {
        $this->elements = $data;
    }

    /**
     * Count elements of an object
     * @link  http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return protocol is cast to an integer.
     * @since 5.1.0
     */
    public function count(): int
    {
        return count($this->elements);
    }

    /**
     * Retrieve an external iterator
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->elements);
    }
}
