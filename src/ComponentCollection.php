<?php

namespace Drieschel\UnitsOfMeasurement;

class ComponentCollection implements \Iterator, \Countable
{
    /**
     * @var ComponentInterface[]
     */
    protected $components = [];

    /**
     * ComponentCollection constructor.
     * @param ComponentInterface ...$components
     * @throws ComponentCollectionException
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
     * @throws ComponentCollectionException
     */
    public function filterByClosure(\Closure $closure): self
    {
        return new static(...array_values(array_unique(array_filter($this->components, $closure), \SORT_REGULAR)));
    }

    /**
     * @param string $symbol
     * @return ComponentInterface
     * @throws ComponentCollectionException
     */
    public function get(string $symbol): ComponentInterface
    {
        if (!$this->has($symbol)) {
            throw ComponentCollectionException::unknownSymbol($symbol);
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
     * @throws ComponentCollectionException
     */
    public function merge(ComponentCollection $collection): self
    {
        foreach ($collection as $component) {
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
     * @return void
     */
    public function rewind(): void
    {
        reset($this->components);
    }

    /**
     * @param ComponentInterface $component
     * @return $this
     * @throws ComponentCollectionException
     */
    public function set(ComponentInterface $component): self
    {
        $symbols = [$component->getSymbol()];
        if ($component instanceof AbstractComponent) {
            $symbols = $component->getSymbols();
        }

        foreach ($symbols as $symbol) {
            if ($this->has($symbol)) {
                throw ComponentCollectionException::existingSymbol($symbol);
            }
            $this->components[$this->normalizeSymbol($symbol)] = $component;
        }

        return $this;
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
