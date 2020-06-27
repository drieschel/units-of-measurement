<?php

namespace Drieschel\UnitsOfMeasurement;

class ComponentCollection implements \Iterator, \Countable, \ArrayAccess
{
    /**
     * @var ComponentInterface[]
     */
    protected $components = [];

    /**
     * ComponentCollection constructor.
     * @param ComponentInterface ...$components
     * @throws CollectionException
     */
    public function __construct(ComponentInterface ...$components)
    {
        foreach ($components as $component) {
            $this->set($component);
        }
    }

    /**
     * @return integer
     */
    public function count(): int
    {
        return count($this->components);
    }

    /**
     * @return ComponentInterface|mixed
     */
    public function current(): ?ComponentInterface
    {
        $value = current($this->components);
        return !is_bool($value) ? $value : null;
    }

    /**
     * @param \Closure $closure
     * @return ComponentCollection
     * @throws CollectionException
     */
    public function filterByClosure(\Closure $closure): self
    {
        return new static(...array_values(array_filter($this->toArray(), $closure)));
    }

    /**
     * @param string $symbol
     * @return ComponentInterface
     * @throws CollectionException
     */
    public function get(string $symbol): ComponentInterface
    {
        if (!$this->has($symbol)) {
            throw CollectionException::symbolUnknown($symbol);
        }
        return $this->components[$this->normalizeSymbol($symbol)];
    }

    /**
     * @param string $symbol
     * @return boolean
     */
    public function has(string $symbol): bool
    {
        return isset($this->components[$this->normalizeSymbol($symbol)]);
    }

    /**
     * @return string|null
     */
    public function key(): ?string
    {
        return key($this->components);
    }

    /**
     * @param ComponentCollection $collection
     * @return ComponentCollection
     * @throws CollectionException
     */
    public function merge(ComponentCollection $collection): self
    {
        foreach($collection->toArray() as $component) {
            $this->set($component);
        }
        return $this;
    }

    /**
     * @return void
     */
    public function next(): void
    {
        next($this->components);
    }

    /**
     * @param mixed $symbol
     * @return boolean
     */
    public function offsetExists($symbol): bool
    {
        return $this->has($symbol);
    }

    /**
     * @param string $symbol
     * @return ComponentInterface
     * @throws CollectionException
     */
    public function offsetGet($symbol): ComponentInterface
    {
        return $this->get($symbol);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        //Array has to be readable only
    }

    /**
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        //Array has to be readable only
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        reset($this->components);
    }

    /**
     * @param ComponentInterface $component
     * @return $this
     * @throws CollectionException
     */
    public function set(ComponentInterface $component): self
    {
        $symbols = [$component->getSymbol()];
        if ($component instanceof AbstractComponent) {
            $symbols = $component->getSymbols();
        }

        foreach ($symbols as $symbol) {
            if ($this->has($symbol)) {
                throw CollectionException::symbolExists($symbol);
            }
            $this->components[$this->normalizeSymbol($symbol)] = $component;
        }

        return $this;
    }

    /**
     * @return ComponentInterface[]
     */
    public function toArray(): array
    {
        $components = [];
        foreach($this->components as $component) {
            if(!in_array($component, $components, true)) {
                $components[] = $component;
            }
        }
        return $components;
    }

    /**
     * @return boolean
     */
    public function valid(): bool
    {
        return $this->key() !== null;
    }

    /**
     * @param string $symbol
     * @return string
     */
    protected function normalizeSymbol(string $symbol): string
    {
        return trim($symbol);
    }
}
